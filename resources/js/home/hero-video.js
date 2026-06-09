/**
 * Hero video lazy loader.
 *
 * Estrategia:
 *  - Mobile (<1024px): NO carga video (clase hidden lg:block en el tag).
 *  - Desktop: espera a window.load (post LCP), luego setea src y reproduce.
 *  - Si el usuario tiene "prefers-reduced-motion" no autoplay.
 *  - Connection saver: si saveData o efectivo <= "2g", no carga video.
 *
 * Ahorra ~8 MB de bandwidth en mobile + ~5s de LCP en mobile.
 */
export function initHeroVideo() {
    const video = document.querySelector('video[data-hero-video]');
    if (! video) return;

    // No cargues en mobile (tag tiene hidden en mobile via Tailwind, pero defensa extra)
    if (window.matchMedia('(max-width: 1023px)').matches) return;

    // Respeta prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Respeta data saver / 2g
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn) {
        if (conn.saveData) return;
        if (['slow-2g', '2g'].includes(conn.effectiveType)) return;
    }

    const loadVideo = () => {
        const src = video.dataset.src;
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
