/**
 * Hero video lazy loader.
 *
 * Estrategia:
 *  - El video se muestra en TODOS los tamaños, pero nunca bloquea el render:
 *    se carga después de window.load (post LCP), así el hero pinta al instante
 *    con el poster degradado y el video entra con fade cuando ya está listo.
 *  - Mobile: si existe una versión ligera (data-src-mobile) se usa esa.
 *  - Si el usuario tiene "prefers-reduced-motion" no autoplay.
 *  - Connection saver: si saveData o conexión lenta, no carga video.
 *    En mobile somos más estrictos (también se salta en 3g) para cuidar datos.
 */
export function initHeroVideo() {
    const video = document.querySelector('video[data-hero-video]');
    if (! video) return;

    // Respeta prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const isMobile = window.matchMedia('(max-width: 1023px)').matches;

    // Respeta data saver / conexiones lentas.
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn) {
        if (conn.saveData) return;
        const slow = isMobile ? ['slow-2g', '2g', '3g'] : ['slow-2g', '2g'];
        if (slow.includes(conn.effectiveType)) return;
    }

    const loadVideo = () => {
        const src = (isMobile && video.dataset.srcMobile) ? video.dataset.srcMobile : video.dataset.src;
        if (! src || video.src) return;
        video.src = src;
        video.load();
        const onCanPlay = () => {
            video.play().catch(() => {/* autoplay bloqueado, no romper */});
            video.classList.add('is-ready');
        };
        video.addEventListener('canplay', onCanPlay, { once: true });
    };

    // Espera al window.load (post LCP) + 200ms extra para no competir con otros assets
    if (document.readyState === 'complete') {
        setTimeout(loadVideo, 200);
    } else {
        window.addEventListener('load', () => setTimeout(loadVideo, 200), { once: true });
    }
}
