{{-- Ajakan penutup (Action), gaya CTA beranda. Param: $before, $highlight, $after, $subtitle, $button. --}}
<section class="cta-section cta-section--default position-relative overflow-hidden" data-reveal>
    <div class="container position-relative">
        <div class="cta-card">
            <span class="cta-ring cta-ring--left" aria-hidden="true"></span>
            <span class="cta-ring cta-ring--right" aria-hidden="true"></span>
            <div class="cta-content">
                <h2 class="cta-title">{{ $before }} <span class="cta-highlight">{{ $highlight }}</span>{{ $after }}</h2>
                <p class="cta-subtitle">{{ $subtitle }}</p>
                <div class="cta-buttons">
                    <a href="https://app.bizbos.id/auth/register" class="btn btn-lg cta-btn-primary">{{ $button }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    <a href="{{ route('frontend.contact') }}" class="btn btn-lg cta-btn-secondary">Tanya Tim Kami</a>
                </div>
            </div>
        </div>
    </div>
</section>
