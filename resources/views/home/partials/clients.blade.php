<!-- ===== OUR CLIENT ===== -->
<section class="section clients-section" id="clients" aria-labelledby="clients-title">
    <div class="container">
        <div class="clients-header">
            <div>
                <span class="label">Built on Collaboration</span>
                <h2 id="clients-title">Our <em>Client</em></h2>
            </div>
            <div class="clients-intro">
                <p>{{ $clients->isNotEmpty() ? 'A selection of the brands and businesses we work with — bringing their ideas to life through design and technology.' : 'Every great collaboration starts with a conversation. Let’s bring your next idea to life.' }}</p>
                <a href="{{ route('contact') }}" class="btn">
                    <span>Work With Us</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        @if ($clients->isNotEmpty())
        <ul class="clients-grid" aria-label="Our clients" role="list">
            @foreach ($clients as $client)
            <li class="client-card">
                <span class="client-name">{{ $client }}</span>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</section>
