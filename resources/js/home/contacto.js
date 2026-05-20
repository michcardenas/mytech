import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /contacto — Animaciones premium del formulario.
 *
 * Guarded por [data-cnt-hero]. Activa:
 *   1. Word-stagger del título hero
 *   2. Chip selectors (tipo de proyecto / presupuesto) — toggle + hidden input sync
 *   3. Textarea char counter
 *   4. Smooth scroll al form al hacer click en CTA hero
 *   5. Field reveal por fieldset (scroll-trigger stagger)
 *   6. Submit loading state
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initContacto() {
    if (! document.querySelector('[data-cnt-hero]')) return;

    initTitleStagger();
    initChips();
    initTextareaCounter();
    initSmoothScrollCTA();
    initFieldsetReveal();
    initSubmitLoading();
}

/* ── 1. Word-stagger del título ─────────────────────── */
function initTitleStagger() {
    const title = document.querySelector('[data-cnt-title]');
    if (! title) return;

    const text = title.textContent;
    title.innerHTML = text.split(' ').map(word =>
        `<span class="word">${word}</span>`
    ).join(' ');

    if (reducedMotion()) return;

    const words = title.querySelectorAll('.word');
    gsap.from(words, {
        opacity: 0,
        y: 30,
        rotateX: -25,
        stagger: 0.045,
        duration: 0.9,
        ease: 'power3.out',
        delay: 0.1,
    });
}

/* ── 2. Chips (tipo_proyecto, presupuesto) ──────────── */
function initChips() {
    const groups = document.querySelectorAll('[data-cnt-chip-group]');
    groups.forEach(group => {
        const targetName = group.dataset.cntChipGroup;
        const input = document.getElementById(targetName);
        const chips = group.querySelectorAll('.mt-cnt-chip');

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('is-active'));
                chip.classList.add('is-active');
                if (input) input.value = chip.dataset.chipValue;

                // Pequeña animación de tap
                if (! reducedMotion()) {
                    gsap.fromTo(chip,
                        { scale: 0.97 },
                        { scale: 1, duration: 0.35, ease: 'back.out(2.2)' }
                    );
                }
            });
        });
    });
}

/* ── 3. Char counter del textarea ──────────────────── */
function initTextareaCounter() {
    const ta = document.querySelector('[data-cnt-textarea]');
    const out = document.querySelector('[data-cnt-counter]');
    if (! ta || ! out) return;

    const update = () => {
        out.textContent = ta.value.length;
        if (ta.value.length > 1800) {
            out.style.color = '#EF4444';
        } else if (ta.value.length > 1500) {
            out.style.color = '#F59E0B';
        } else {
            out.style.color = '';
        }
    };
    update();
    ta.addEventListener('input', update);
}

/* ── 4. Smooth scroll del CTA hero al form ─────────── */
function initSmoothScrollCTA() {
    const cta = document.querySelector('[data-cnt-scroll-form]');
    if (! cta) return;

    cta.addEventListener('click', (e) => {
        const form = document.getElementById('form');
        if (! form) return;
        e.preventDefault();
        const offset = 80;
        const top = form.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    });
}

/* ── 5. Reveal por fieldset ────────────────────────── */
function initFieldsetReveal() {
    if (reducedMotion()) return;

    const steps = document.querySelectorAll('[data-cnt-step]');
    steps.forEach(step => {
        gsap.from(step, {
            opacity: 0,
            y: 30,
            duration: 0.7,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: step,
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    });
}

/* ── 6. Submit loading state ───────────────────────── */
function initSubmitLoading() {
    const form = document.querySelector('[data-cnt-form]');
    const btn = document.querySelector('[data-cnt-submit]');
    if (! form || ! btn) return;

    form.addEventListener('submit', () => {
        // No prevenimos el submit — solo añadimos el estado visual
        // La validación HTML5 ya falla antes si hay errores
        if (form.checkValidity()) {
            btn.classList.add('is-loading');
            btn.disabled = true;
        }
    });
}
