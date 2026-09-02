<!-- ===== OUR CLIENT ===== -->
<section class="section clients-section" id="clients" aria-labelledby="clients-title" data-client-marquee>
    <div class="container">
        <div class="services-header clients-header">
            <div class="clients-heading">
                <span class="label">Clients & Partners</span>
                <h2 id="clients-title">Our<br><em>Client</em></h2>
            </div>
            <div class="clients-intro">
                <p>{{ $clients->isNotEmpty() ? 'The brands and businesses we collaborate with, bringing ideas to life through thoughtful design and digital experiences.' : 'Every great collaboration starts with a conversation. Let us bring your next idea to life.' }}</p>
                @if ($clients->isNotEmpty())
                <button type="button" class="clients-toggle" data-client-toggle aria-controls="clients-logos" aria-pressed="false" hidden>Jeda animasi</button>
                @else
                <a href="{{ route('contact') }}" class="btn">
                    <span>Work With Us</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($clients->isNotEmpty())
    @php($repetitions = max(1, (int) ceil(8 / $clients->count())))
    <div class="container clients-logos">
    <div class="clients-viewport" id="clients-logos" tabindex="0" role="region" aria-label="Logo klien dan mitra; animasi berhenti saat area ini difokuskan">
        @foreach ([false, true] as $reverse)
        <div class="clients-row {{ $reverse ? 'clients-row--reverse' : '' }}" @if ($reverse) aria-hidden="true" @endif>
            <div class="clients-track" style="--clients-duration: {{ max(32, $clients->count() * $repetitions * 4) }}s">
                @foreach ([false, true] as $copy)
                <ul class="clients-group {{ $copy ? 'clients-group--copy' : '' }}" role="list" @if ($copy) aria-hidden="true" @endif>
                    @for ($repeat = 0; $repeat < $repetitions; $repeat++)
                    @foreach ($reverse ? $clients->reverse() : $clients as $client)
                    <li class="client-logo {{ $repeat > 0 ? 'client-logo--repeat' : '' }}" @if ($repeat > 0) aria-hidden="true" @endif>
                        <img src="{{ asset('storage/'.$client->logo) }}" alt="{{ $client->name }}" width="180" height="78" loading="lazy" decoding="async" data-client-logo>
                        <span class="client-logo-fallback" hidden>{{ $client->name }}</span>
                    </li>
                    @endforeach
                    @endfor
                </ul>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    </div>
    <script type="module" src="{{ asset('js/clients.js') }}"></script>
    @endif
</section>
