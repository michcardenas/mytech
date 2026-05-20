import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Servicios — scroll storytelling.
 *
 * Desktop (>= 1024): sticky title lateral + cards con scrub de opacidad
 *                    según pasan por el viewport (efecto pin original).
 * Mobile  (< 1024):  cards atenuadas por defecto (CSS), se iluminan
 *                    cuando entran a la zona central del viewport (JS).
 *                    Headers de categoría se quedan sticky (CSS).
 *
 * Ambos caminos respetan prefers-reduced-motion.
 */
export function initPinServicios() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    if (window.innerWidth >= 1024) {
        initDesktopPin();
    } else {
        initMobileFocus();
    }
}

/* ── Desktop: comportamiento original ─────────────────────────────── */
function initDesktopPin() {
    const section = document.querySelector('[data-pin-servicios]');
    const sticky  = document.querySelector('[data-pin-servicios-sticky]');
    const cards   = document.querySelectorAll('[data-pin-servicios-card]');
    if (!section || !sticky || cards.length === 0) return;

    gsap.set(cards, { opacity: 0.15, y: 0 });
    gsap.set(cards[0], { opacity: 1 });

    cards.forEach((card) => {
        ScrollTrigger.create({
            trigger: card,
            start: 'top 60%',
            end:   'top 40%',
            onEnter:     () => gsap.to(card, { opacity: 1,    duration: 0.5 }),
            onLeave:     () => gsap.to(card, { opacity: 0.25, duration: 0.5 }),
            onEnterBack: () => gsap.to(card, { opacity: 1,    duration: 0.5 }),
            onLeaveBack: () => gsap.to(card, { opacity: 0.15, duration: 0.5 }),
        });
    });
}

/* ── Mobile: focus scrub vertical ─────────────────────────────────── */
function initMobileFocus() {
    const cards = document.querySelectorAll('[data-pin-servicios-card]');
    if (cards.length === 0) return;

    // CSS ya pone opacity:0.32 por defecto en mobile. Agregamos `.is-in-focus`
    // cuando la card está en la zona central del viewport.
    cards.forEach((card) => {
        ScrollTrigger.create({
            trigger: card,
            start: 'top 78%',
            end:   'bottom 22%',
            onEnter:     () => card.classList.add('is-in-focus'),
            onLeave:     () => card.classList.remove('is-in-focus'),
            onEnterBack: () => card.classList.add('is-in-focus'),
            onLeaveBack: () => card.classList.remove('is-in-focus'),
        });
    });

    // En resize importante (rotación, abrir devtools), refresh ScrollTrigger
    let lastWidth = window.innerWidth;
    window.addEventListener('resize', () => {
        if (Math.abs(window.innerWidth - lastWidth) > 50) {
            lastWidth = window.innerWidth;
            ScrollTrigger.refresh();
        }
    }, { passive: true });
}
