import Lenis from 'lenis';

export function initLenis() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return null;
    }

    const isTouchDevice = ('ontouchstart' in window) || navigator.maxTouchPoints > 0;

    const lenis = new Lenis({
        duration: 1.4,
        // easing tipo "Apple smooth" — natural, sin overshoot
        easing: (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        // CRITICAL: NO syncTouch en mobile.
        // syncTouch (alias deprecated: smoothTouch) intercepta touchmove e
        // INTERFIERE con los taps sobre anchor links — el primer tap se come
        // y el usuario tiene que tocar 2 veces para navegar.
        // Dejamos que el navegador maneje touch nativamente (es perfecto en
        // iOS/Android modernos y respeta clicks/taps al instante).
        syncTouch: false,
        smoothTouch: false,
        wheelMultiplier: 0.95,
        touchMultiplier: 1.8,
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
