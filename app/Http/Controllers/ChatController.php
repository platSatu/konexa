<?php

namespace App\Http\Controllers;

use App\Services\TeleiosApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Live chat bizbos.id: pesan pengunjung diteruskan ke app.bizbos (POST /api/frontend/chat)
 * lewat server, supaya API key tidak pernah sampai ke browser. Jawaban dibuat dari konten
 * website (FAQ, fitur, paket) di app.bizbos.
 */
class ChatController extends Controller
{
    public function __construct(private readonly TeleiosApiService $teleiosApi)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ]);

        $response = $this->teleiosApi->chat($data + ['visitor' => (string) $request->ip()]);

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
