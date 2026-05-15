import Lenis from 'lenis';

export function initLenis() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return null;
    }

    const isTouchDevice = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;

    const lenis = new Lenis({
        // duration mayor → más suave, más profesional
        duration: 1.4,
        // easing tipo "Apple smooth" — natural, sin overshoot
        easing: (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        // smoothTouch en mobile mejora MUCHO la sensación del pin scroll
        smoothTouch: isTouchDevice,
        wheelMultiplier: 0.95,
        touchMultiplier: isTouchDevice ? 2.2 : 1.8,
        lerp: 0.085,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    window.__lenis = lenis;
    return lenis;
}
