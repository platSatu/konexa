<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Live chat bizbos.id: pesan pengunjung diteruskan ke app.bizbos (POST /api/frontend/chat)
 * lewat server, supaya API key tidak pernah sampai ke browser. Jawaban dibuat dari konten
 * website (FAQ, fitur, paket) di app.bizbos.
 * Pengunjung wajib mengisi nama, email, WhatsApp dulu (start); namanya disimpan di sesi
 * dan dipakai untuk menyapa di setiap jawaban.
 */
class ChatController extends Controller
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $response = $this->teleiosApi->chat($data, '/api/frontend/chat/start');

        if ($response?->successful()) {
            $request->session()->put('chat_lead', (string) $response->json('data.name'));

            return response()->json(['name' => $response->json('data.name')]);
        }

        // Pesan validasi dari app.bizbos diteruskan apa adanya supaya pengunjung tahu isian mana yang salah.
        if ($response?->status() === 422) {
            return response()->json(['message' => 'Periksa lagi isian Anda.', 'errors' => $response->json('errors', [])], 422);
        }

        return response()->json(['message' => 'Maaf, live chat sedang sibuk. Tim kami tetap siap membantu lewat WhatsApp ya.'], 503);
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (! $request->session()->has('chat_lead')) {
            return response()->json(['reply' => 'Isi nama, email, dan nomor WhatsApp dulu ya supaya kami bisa membantu.', 'needs_start' => true], 422);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ]);

        $response = $this->teleiosApi->chat($data + ['visitor' => (string) $request->ip(), 'name' => (string) $request->session()->get('chat_lead')]);

        if ($response?->successful()) {
            return response()->json($response->json('data'));
        }

        return response()->json([
            'reply' => $response?->status() === 429
                ? (string) $response->json('message')
                : 'Maaf, live chat sedang sibuk. Tim kami tetap siap membantu lewat WhatsApp ya.',
        ], $response?->status() === 429 ? 429 : 200);
    }
}
