import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Scroll storytelling — escenas premium con GSAP ScrollTrigger.
 *
 * Todas las escenas:
 *   - respetan prefers-reduced-motion
 *   - usan trigger por sección, no por viewport global
 *   - usan scrub donde aporta narrativa, toggleActions donde es entry/exit
 *
 * Cada init* es independiente. Si una falla, las demás siguen funcionando.
 */

const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ─────────────────────────────────────────────────────────────────────
   1. HERO — parallax sutil del texto al hacer scroll inicial
   El video queda fijo, el bloque de texto sube con yPercent negativo.
   Solo desktop (en mobile el viewport es corto y se ve raro).
   ───────────────────────────────────────────────────────────────────── */
export function initHeroParallax() {
    if (reducedMotion()) return;
    if (window.innerWidth < 1024) return;

    const hero       = document.querySelector('section.min-h-screen');
    const textBlock  = hero?.querySelector('.max-w-4xl');
    const videoBg    = hero?.querySelector('.hero-video-bg');
    if (!hero || !textBlock) return;

    // Texto sube ~10% durante el primer viewport
    gsap.to(textBlock, {
        yPercent: -10,
        opacity:  0.6,
        ease:     'none',
        scrollTrigger: {
            trigger: hero,
            start:   'top top',
            end:     'bottom top',
            scrub:   true,
        },
    });

    // Video se mueve un poquito MENOS para sensación de profundidad
    if (videoBg) {
        gsap.to(videoBg, {
            yPercent: 15,
            ease:     'none',
            scrollTrigger: {
                trigger: hero,
                start:   'top top',
                end:     'bottom top',
                scrub:   true,
            },
        });
    }
}

/* ─────────────────────────────────────────────────────────────────────
   2. CASOS — cascada DIAGONAL (no stagger lineal aburrido)
   Cada card entra con delay basado en su fila Y columna.
   Sobreescribe el data-animate-children genérico para esta sección.
   ───────────────────────────────────────────────────────────────────── */
export function initCasosCascade() {
    if (reducedMotion()) return;

    const grid = document.querySelector('[data-casos-grid]');
    if (!grid) return;

    const cards = grid.querySelectorAll(':scope > a');
    if (cards.length === 0) return;

    // Inferir columnas según viewport actual (Tailwind: 1 / 2 / 3 cols)
    const w   = window.innerWidth;
    const cols = w >= 1024 ? 3 : (w >= 640 ? 2 : 1);

    cards.forEach((card, idx) => {
        const row = Math.floor(idx / cols);
        const col = idx % cols;
        // Delay diagonal: row + col → onda noroeste-sureste
        const delay = (row + col) * 0.08;

        gsap.fromTo(card,
            { opacity: 0, y: 40, scale: 0.97 },
            {
                opacity:   1,
                y:         0,
                scale:     1,
                duration:  0.9,
                ease:      'power3.out',
                delay,
                scrollTrigger: {
                    trigger: grid,
                    start:   'top 80%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });
}

/* ─────────────────────────────────────────────────────────────────────
   3. STACK — 3D tilt suave en hover de cada logo
   Pure CSS perspective + JS mousemove. No usa lib.
   ───────────────────────────────────────────────────────────────────── */
export function initStackTilt() {
    if (reducedMotion()) return;
    if (('ontouchstart' in window) || navigator.maxTouchPoints > 0) return;

    document.querySelectorAll('.mt-stack-chip').forEach((chip) => {
        chip.style.transformStyle = 'preserve-3d';
        chip.style.transition     = 'transform 250ms cubic-bezier(0.22, 1, 0.36, 1)';

        chip.addEventListener('mousemove', (e) => {
            const rect = chip.getBoundingClientRect();
            const x    = (e.clientX - rect.left) / rect.width  - 0.5;
            const y    = (e.clientY - rect.top)  / rect.height - 0.5;
            // Tilt máximo ±6° — sutil, no exagerado
            const rx = (-y * 6).toFixed(2);
            const ry = ( x * 6).toFixed(2);
            chip.style.transform = `perspective(600px) rotateX(${rx}deg) rotateY(${ry}deg) translateY(-2px)`;
        });

        chip.addEventListener('mouseleave', () => {
            chip.style.transform = '';
        });
    });
}

/* ─────────────────────────────────────────────────────────────────────
   4. PROCESO — scrub del número grande mientras avanza el pin horizontal
   El número del paso activo crece sutilmente; los inactivos se desvanecen.
   La barra de progreso ya está animada por process-pin.js, esto la complementa.
   ───────────────────────────────────────────────────────────────────── */
export function initProcesoScrub() {
    if (reducedMotion()) return;
    if (window.innerWidth < 1024) return;

    const steps = document.querySelectorAll('[data-process-step]');
    if (steps.length === 0) return;

    steps.forEach((step) => {
        const num = step.querySelector('.mt-process-step-num');
        if (!num) return;

        // Estado base: número discreto
        gsap.set(num, { scale: 1, opacity: 0.75 });

        // Cuando esta step gana la clase `.is-snap-active`, el num se enfatiza.
        // Observamos con MutationObserver en vez de scrub porque process-pin.js
        // ya controla qué step está activo via clase.
        const observer = new MutationObserver(() => {
            if (step.classList.contains('is-snap-active')) {
                gsap.to(num, { scale: 1.04, opacity: 1, duration: 0.6, ease: 'power3.out' });
            } else {
                gsap.to(num, { scale: 1, opacity: 0.55, duration: 0.6, ease: 'power3.out' });
            }
        });
        observer.observe(step, { attributes: true, attributeFilter: ['class'] });
    });
}

/* ─────────────────────────────────────────────────────────────────────
   5. CTA dark — entrada con fade + scale al llegar al viewport
   Da sensación de "cierre" del scroll, no de "otro bloque más".
   ───────────────────────────────────────────────────────────────────── */
export function initCtaEntry() {
    if (reducedMotion()) return;

    const cta  = document.querySelector('.mt-cta-dark');
    if (!cta) return;
    const inner = cta.querySelector('.max-w-3xl');
    if (!inner) return;

    gsap.fromTo(inner,
        { opacity: 0, y: 50, scale: 0.96 },
        {
            opacity:  1,
            y:        0,
            scale:    1,
            duration: 1.3,
            ease:     'power3.out',
            scrollTrigger: {
                trigger: cta,
                start:   'top 75%',
                toggleActions: 'play none none none',
            },
        }
    );
}

/* ─────────────────────────────────────────────────────────────────────
   Master init — todas las escenas
   ───────────────────────────────────────────────────────────────────── */
export function initScrollStorytelling() {
    initHeroParallax();
    initCasosCascade();
    initStackTilt();
    initProcesoScrub();
    initCtaEntry();
}
