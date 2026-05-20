import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /blog — animaciones premium del listado de artículos.
 *
 * Guarded — solo activa si encuentra [data-blog-hero].
 *
 * Escenas:
 *   1. Hero: char-stagger del título + parallax del watermark
 *   2. Featured card: subtle reveal al entrar viewport
 *   3. Grid cards: stagger reveal (delegado a data-animate-children genérico)
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initBlogStorytelling() {
    if (! document.querySelector('[data-blog-hero]')) return;

    initBlogHero();
    initBlogFeatured();
}

/* ── HERO — char-stagger + watermark parallax ─────────── */
function initBlogHero() {
    if (reducedMotion()) return;

    const titleEl = document.querySelector('[data-blog-hero-title]');
    const watermark = document.querySelector('[data-blog-watermark]');

    if (titleEl) {
        const spans = titleEl.querySelectorAll(':scope > span');
        spans.forEach((span) => {
            const text = span.textContent;
            span.innerHTML = text.split('').map(ch =>
                ch === ' '
                    ? '<span class="char-space">&nbsp;</span>'
                    : `<span class="char">${ch}</span>`
            ).join('');
        });
        const chars = titleEl.querySelectorAll('.char');
        gsap.from(chars, {
            opacity: 0,
            y: 60,
            rotateX: -30,
            stagger: 0.024,
            duration: 1.1,
            ease: 'power4.out',
            delay: 0.1,
        });
    }

    if (watermark) {
        gsap.to(watermark, {
            yPercent: -25,
            opacity: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: '[data-blog-hero]',
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    }
}

/* ── FEATURED card — reveal sutil al entrar viewport ───── */
function initBlogFeatured() {
    if (reducedMotion()) return;

    const card = document.querySelector('[data-blog-featured-card]');
    if (! card) return;

    gsap.set(card, { opacity: 0, y: 40 });
    ScrollTrigger.create({
        trigger: card,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.to(card, { opacity: 1, y: 0, duration: 1, ease: 'power3.out' });
        },
    });
}
