<!-- ===== OUR CLIENT ===== -->
<section class="clients-section" id="clients" aria-labelledby="clients-title" data-client-marquee>
    <div class="container">
        <div class="clients-header">
            <div class="clients-heading">
                <span class="label">Client & Partner</span>
                <h2 id="clients-title">Klien dan Mitra Kami</h2>
            </div>
            <div class="clients-intro">
                <p>{{ $clients->isNotEmpty() ? 'Bersama klien dan mitra, kami menghadirkan ide menjadi identitas, karya, dan pengalaman digital yang bermakna.' : 'Setiap kolaborasi dimulai dari percakapan. Mari wujudkan ide dan langkah berikutnya untuk brand Anda.' }}</p>
                @if ($clients->isNotEmpty())
                <button type="button" class="clients-toggle" data-client-toggle aria-controls="clients-logos" aria-pressed="false" hidden>Jeda animasi</button>
                @else
                <a href="{{ route('contact') }}" class="clients-contact">Mulai Kolaborasi →</a>
                @endif
            </div>
        </div>
    </div>

    @if ($clients->isNotEmpty())
    @php($repetitions = max(1, (int) ceil(8 / $clients->count())))
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
    <script type="module" src="{{ asset('js/clients.js') }}"></script>
    @endif
</section>
