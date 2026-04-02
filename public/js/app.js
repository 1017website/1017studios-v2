// ============================================
// 1017STUDIOS — MAIN JAVASCRIPT v2
// ============================================

document.addEventListener('DOMContentLoaded', () => {

    // ================================================
    // CUSTOM CURSOR — always visible, smooth tracking
    // ================================================
    const cursor   = document.getElementById('cursor');
    const follower = document.getElementById('cursorFollower');

    if (cursor && follower) {
        let mouseX = window.innerWidth / 2;
        let mouseY = window.innerHeight / 2;
        let followerX = mouseX;
        let followerY = mouseY;
        let rafId;

        // Set initial position immediately so cursor doesn't start at 0,0
        cursor.style.left   = mouseX + 'px';
        cursor.style.top    = mouseY + 'px';
        follower.style.left = mouseX + 'px';
        follower.style.top  = mouseY + 'px';

        // Track mouse — use clientX/Y, not pageX/Y (stays correct during scroll)
        document.addEventListener('mousemove', e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        // Smooth follower via RAF loop
        function animateCursor() {
            // Dot snaps instantly
            cursor.style.left = mouseX + 'px';
            cursor.style.top  = mouseY + 'px';

            // Follower lerps smoothly
            followerX += (mouseX - followerX) * 0.10;
            followerY += (mouseY - followerY) * 0.10;
            follower.style.left = followerX + 'px';
            follower.style.top  = followerY + 'px';

            rafId = requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // Hover state on interactive elements
        const interactiveEls = 'a, button, [role="button"], input, textarea, select, label[for]';

        document.addEventListener('mouseover', e => {
            if (e.target.matches(interactiveEls) || e.target.closest(interactiveEls)) {
                cursor.classList.add('hover');
                follower.classList.add('hover');
            }
        });
        document.addEventListener('mouseout', e => {
            if (e.target.matches(interactiveEls) || e.target.closest(interactiveEls)) {
                cursor.classList.remove('hover');
                follower.classList.remove('hover');
            }
        });

        // Hide when mouse leaves window, show when it returns
        document.addEventListener('mouseleave', () => {
            cursor.style.opacity   = '0';
            follower.style.opacity = '0';
        });
        document.addEventListener('mouseenter', () => {
            cursor.style.opacity   = '1';
            follower.style.opacity = '1';
        });
    }

    // ================================================
    // NAV SCROLL
    // ================================================
    const nav = document.getElementById('mainNav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // run once on load
    }

    // ================================================
    // HAMBURGER / MOBILE MENU
    // ================================================
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger?.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu?.classList.toggle('open');
        document.body.style.overflow = mobileMenu?.classList.contains('open') ? 'hidden' : '';
    });

    mobileMenu?.querySelectorAll('.mobile-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger?.classList.remove('open');
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        });
    });

    // ================================================
    // SCROLL REVEAL
    // ================================================
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

        revealEls.forEach(el => io.observe(el));
    }

    // ================================================
    // COUNTER ANIMATION
    // ================================================
    const counters = document.querySelectorAll('.stat-num[data-count]');
    if (counters.length) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const el      = e.target;
                const target  = parseInt(el.dataset.count);
                const suffix  = el.dataset.suffix || '';
                const duration = 1800;
                const start   = performance.now();

                function update(now) {
                    const elapsed  = now - start;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased    = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(eased * target) + suffix;
                    if (progress < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.5 });

        counters.forEach(c => counterObserver.observe(c));
    }

    // ================================================
    // MARQUEE CLONE (for infinite scroll)
    // ================================================
    const track = document.querySelector('.marquee-track');
    if (track && !track.dataset.cloned) {
        track.dataset.cloned = 'true';
        const clone = track.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.parentElement.appendChild(clone);
    }

    // ================================================
    // HERO TITLE STAGGER
    // ================================================
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const spans = heroTitle.querySelectorAll('span');
        spans.forEach((span, i) => {
            span.style.opacity   = '0';
            span.style.transform = 'translateY(36px)';
            span.style.transition = `opacity 1s cubic-bezier(0.16,1,0.3,1) ${0.15 + i * 0.14}s, transform 1s cubic-bezier(0.16,1,0.3,1) ${0.15 + i * 0.14}s`;
            // Trigger after a tiny delay so transition fires
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    span.style.opacity   = '1';
                    span.style.transform = 'translateY(0)';
                });
            });
        });
    }

    // ================================================
    // FLASH MESSAGE AUTO DISMISS
    // ================================================
    document.querySelectorAll('.flash-message').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4500);
    });

    // ================================================
    // IMAGE FILE PREVIEW (admin uploads)
    // ================================================
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        const preview = document.getElementById(input.dataset.preview);
        if (!preview) return;
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                const url     = URL.createObjectURL(file);
                preview.src   = url;
                preview.style.display = 'block';
                const placeholder = document.getElementById('preview-placeholder');
                if (placeholder) placeholder.style.display = 'none';
            }
        });
    });

    // ================================================
    // SMOOTH ANCHOR SCROLL
    // ================================================
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id     = a.getAttribute('href');
            const target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                const offset = target.getBoundingClientRect().top + window.scrollY - 90;
                window.scrollTo({ top: offset, behavior: 'smooth' });
            }
        });
    });

    // ================================================
    // ACTIVE ADMIN NAV
    // ================================================
    document.querySelectorAll('.admin-nav-item[href]').forEach(link => {
        if (link.getAttribute('href') === window.location.pathname) {
            link.classList.add('active');
        }
    });

});
