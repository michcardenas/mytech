import { gsap } from 'gsap';

export function initMarquee() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.querySelectorAll('[data-marquee]').forEach((track) => {
        const direction = track.dataset.marquee === 'reverse' ? 1 : -1;
        const speed = parseFloat(track.dataset.marqueeSpeed || '40');

        // Duplicar contenido para loop infinito
        const items = Array.from(track.children);
        items.forEach((item) => {
            const clone = item.cloneNode(true);
            track.appendChild(clone);
        });

        const totalWidth = track.scrollWidth / 2;

        gsap.set(track, { x: direction === -1 ? 0 : -totalWidth });
        gsap.to(track, {
            x: direction === -1 ? -totalWidth : 0,
            duration: speed,
            ease: 'none',
            repeat: -1,
        });
    });
}
