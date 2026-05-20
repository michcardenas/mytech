import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /sobre-nosotros — Manifiesto cinemático (defensivo).
 *
 * PRINCIPIO: el contenido se ve por defecto. Las animaciones de entrada
 * solo aplican transformaciones SUTILES que no dejan contenido oculto si
 * algo falla. Si necesitas hide-on-load, usa CSS con clase ".is-revealing".
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;

export function initSobreNosotros() {
    if (! document.querySelector('[data-sn-prologo]')) return;

    document.documentElement.classList.add('sn-js-ready');

    initPrologo();
    initTesisProgress();
    initNumerosCounters();
    initGenteTilt();
    initFinReveal();
}

/* ────────────────────────────────────────────────
   00 — PRÓLOGO  (word-stagger CSS-based)
   ──────────────────────────────────────────────── */
function initPrologo() {
    const title = document.querySelector('[data-sn-shutter]');
    if (title) {
        const text = title.textContent.trim();
        title.innerHTML = text.split(' ').map((w, i) =>
            `<span class="mt-sn-word" style="--word-delay: ${i * 0.08}s">${w}</span>`
        ).join(' ');
        if (! reducedMotion()) title.classList.add('is-revealing');
    }

    // Cursor glow
    const glow = document.querySelector('[data-sn-cursor]');
    const stage = document.querySelector('[data-sn-prologo]');
    if (glow && stage && isDesktop() && ! ('ontouchstart' in window) && ! reducedMotion()) {
        const xTo = gsap.quickTo(glow, 'x', { duration: 0.55, ease: 'power3' });
        const yTo = gsap.quickTo(glow, 'y', { duration: 0.55, ease: 'power3' });
        stage.addEventListener('mousemove', (e) => {
            const rect = stage.getBoundingClientRect();
            xTo(e.clientX - rect.left);
            yTo(e.clientY - rect.top);
        });
    }

    // Scroll cue smooth scroll
    const cue = document.querySelector('[data-sn-scroll-cue]');
    if (cue) {
        cue.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.getElementById('tesis');
            if (! target) return;
            window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - 80, behavior: 'smooth' });
        });
    }
}

/* ────────────────────────────────────────────────
   01 — TESIS  (word reveal scrub, legible siempre)
   ──────────────────────────────────────────────── */
function initTesisProgress() {
    const section = document.querySelector('[data-sn-tesis]');
    if (! section) return;
    const words = section.querySelectorAll('[data-sn-tesis-word]');
    if (words.length === 0 || reducedMotion()) return;

    // Empiezan tenues pero legibles (0.35) y se completan al scroll.
    gsap.set(words, { opacity: 0.35 });

    gsap.to(words, {
        opacity: 1,
        stagger: { each: 0.04, ease: 'none' },
        duration: 0.6,
        ease: 'none',
        scrollTrigger: {
            trigger: section,
            start: 'top 70%',
            end: 'bottom 50%',
            scrub: 0.5,
        },
    });
}

/* ────────────────────────────────────────────────
   02 — OPERATIONS CONSOLE
   ──────────────────────────────────────────────── */
function initNumerosCounters() {
    const console = document.querySelector('[data-sn-console]');
    if (! console) return;

    // Typing del comando cuando entra la consola
    const cmd = console.querySelector('[data-sn-typing]');
    if (cmd && ! reducedMotion()) {
        ScrollTrigger.create({
            trigger: console,
            start: 'top 80%',
            once: true,
            onEnter: () => cmd.classList.add('is-typing'),
        });
    }

    // Reveal de los tiles con stagger + count-up + sparkline draw
    const tiles = console.querySelectorAll('[data-sn-tile]');
    tiles.forEach((tile, idx) => {
        ScrollTrigger.create({
            trigger: tile,
            start: 'top 88%',
            once: true,
            onEnter: () => {
                // Stagger pequeño basado en el índice
                const delay = reducedMotion() ? 0 : idx * 120 + 400; // espera al typing
                setTimeout(() => {
                    tile.classList.add('is-revealed');
                    runCounter(tile);
                }, delay);
            },
            // Defensive: si el scroll está más allá al refresh, marcar revelado
            onRefresh: (self) => {
                if (self.progress > 0 && ! tile.classList.contains('is-revealed')) {
                    tile.classList.add('is-revealed');
                    runCounter(tile, true); // skip animation, set final value
                }
            },
        });
    });
}

function runCounter(tile, instant = false) {
    const counter = tile.querySelector('[data-sn-counter]');
    if (! counter) return;
    const to = parseInt(counter.dataset.to, 10) || 0;
    const original = counter.textContent;
    if (to === 0 || instant || reducedMotion()) {
        counter.textContent = original;
        return;
    }
    const obj = { val: 0 };
    gsap.to(obj, {
        val: to,
        duration: 1.4,
        ease: 'power2.out',
        onUpdate: () => { counter.textContent = Math.round(obj.val); },
        onComplete: () => { counter.textContent = original; },
    });
}

/* ────────────────────────────────────────────────
   04 — GENTE  (solo tilt photo en hover, sin entrada)
   ──────────────────────────────────────────────── */
function initGenteTilt() {
    if (! isDesktop() || reducedMotion()) return;
    const tilts = document.querySelectorAll('[data-sn-tilt]');
    tilts.forEach(card => {
        const img = card.querySelector('img, .mt-sn-miembro-photo-empty');
        if (! img) return;
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            gsap.to(img, {
                rotateY: x * 5,
                rotateX: -y * 5,
                scale: 1.02,
                transformPerspective: 800,
                duration: 0.5,
                ease: 'power2.out',
            });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(img, { rotateY: 0, rotateX: 0, scale: 1, duration: 0.6, ease: 'power2.out' });
        });
    });
}

/* ────────────────────────────────────────────────
   05 — FIN.  (escala + blur al entrar — único reveal del cap 05)
   ──────────────────────────────────────────────── */
function initFinReveal() {
    const fin = document.querySelector('[data-sn-fin]');
    if (! fin || reducedMotion()) return;

    const text = fin.querySelector('.mt-sn-fin-text');
    if (! text) return;

    gsap.fromTo(text,
        { scale: 0.88, opacity: 0.4, filter: 'blur(12px)' },
        {
            scale: 1, opacity: 1, filter: 'blur(0px)',
            duration: 1.0,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: fin,
                start: 'top 80%',
                once: true,
            },
        }
    );
}
