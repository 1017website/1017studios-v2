export function initClientMarquee(section, motion = window.matchMedia('(prefers-reduced-motion: reduce)')) {
    const button = section.querySelector('[data-client-toggle]');
    if (!button || section.dataset.clientReady) return;
    section.dataset.clientReady = 'true';

    const syncMotion = () => {
        section.classList.toggle('clients-animated', !motion.matches);
        button.hidden = motion.matches;
    };

    button.addEventListener('click', () => {
        const paused = section.classList.toggle('is-paused');
        button.setAttribute('aria-pressed', String(paused));
        button.textContent = paused ? 'Lanjutkan animasi' : 'Jeda animasi';
    });

    section.querySelectorAll('[data-client-logo]').forEach(img => {
        const showName = () => {
            img.hidden = true;
            img.nextElementSibling.hidden = false;
        };
        img.addEventListener('error', showName);
        if (img.complete && img.naturalWidth === 0) showName();
    });

    motion.addEventListener('change', syncMotion);
    syncMotion();
}

if (typeof document !== 'undefined') {
    document.querySelectorAll('[data-client-marquee]').forEach(section => initClientMarquee(section));
}
