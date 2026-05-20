import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /servicios — Scroll storytelling cinematográfico.
 *
 * Desktop (≥1024): pin scrub vertical de 6 slides, una sola timeline
 *                  controla cross-fades + progress + dots.
 * Mobile  (<1024): SIN pin (batería/jank). Cada slide se vuelve estático
 *                  con fade-in al entrar viewport. CSS controla layout vertical.
 * Reduced motion:  todos los slides visibles, sin animaciones.
 *
 * Guarded by selector — si no hay [data-services-pin], no hace nada
 * (así no interfiere con /home u otras páginas).
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initServicesStorytelling() {
    const section = document.querySelector('[data-services-pin]');
    if (!section) return;  // guard: solo en /servicios

    if (reducedMotion()) {
        applyReducedMotionFallback(section);
        return;
    }

    if (window.innerWidth >= 1024) {
        initDesktopPinScrub(section);
    } else {
        initMobileStaticFade(section);
    }
}

/* ─────────────────────────────────────────────────────────────────────
   DESKTOP — Pin scrub timeline única
   ───────────────────────────────────────────────────────────────────── */
function initDesktopPinScrub(section) {
    const stage   = section.querySelector('[data-services-stage]');
    const slides  = section.querySelectorAll('[data-services-slide]');
    const dots    = section.querySelectorAll('[data-services-dot]');
    const progFill = section.querySelector('[data-services-progress-fill]');
    const progNum  = section.querySelector('[data-services-progress-num]');
    const progCat  = section.querySelector('[data-services-progress-cat]');

    if (!stage || slides.length === 0) return;

    const total = slides.length;

    // Estado inicial: todos invisibles excepto el primero
    gsap.set(slides, { opacity: 0, scale: 1.04, y: 30 });
    gsap.set(slides[0], { opacity: 1, scale: 1, y: 0 });

    // Activar will-change durante el pin para mejor compositing
    slides.forEach(s => { s.style.willChange = 'opacity, transform'; });

    // Categorías cacheadas para el progress label
    const categories = Array.from(slides).map(s => {
        const dot = section.querySelector(`[data-services-dot][data-index="${s.dataset.index}"]`);
        return dot?.querySelector('.mt-services-dot-label')?.textContent || '';
    });
    // El progress-cat viene del attribute en cada slide via PHP — leemos del DOM
    const slideCategoryLabels = Array.from(slides).map(s => {
        // El partial inyecta esto en el dot. Como fallback leemos directo.
        const cat = s.querySelector('.mt-services-slide-copy span[style*="--slide-tint"]');
        return cat?.textContent?.trim() || '';
    });

    // Timeline maestra con scrub — una sola ScrollTrigger
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end:   () => `+=${section.offsetHeight - window.innerHeight}`,
            pin: stage,
            scrub: 0.8,
            anticipatePin: 1,
            invalidateOnRefresh: true,
            onUpdate: (self) => {
                // Progress bar
                if (progFill) progFill.style.transform = `scaleX(${self.progress})`;
                // Slide index activo — el dot cambia al pasar la mitad de cada transición
                // Con N transiciones en timeline 0..N, el slide i es "el más visible" cerca de t=i
                const activeIdx = Math.min(total - 1, Math.round(self.progress * (total - 1)));
                if (progNum) progNum.textContent = String(activeIdx + 1).padStart(2, '0');
                if (progCat && slideCategoryLabels[activeIdx]) {
                    progCat.textContent = slideCategoryLabels[activeIdx];
                }
                // Dots activos
                dots.forEach((d, i) => {
                    d.classList.toggle('is-active', i === activeIdx);
                });
                // aria-hidden en slides
                slides.forEach((s, i) => {
                    s.setAttribute('aria-hidden', i === activeIdx ? 'false' : 'true');
                    s.classList.toggle('is-active', i === activeIdx);
                });
            },
        },
    });

    // Cross-fade entre slides: cada par i→i+1 es una transición en la timeline.
    // Las transiciones arrancan en t=0 (no t=1) para eliminar el "dead zone"
    // donde el primer slide se queda fijo demasiado tiempo antes de cambiar.
    for (let i = 1; i < total; i++) {
        const pos = i - 1; // ← era `i`, ahora arranca en 0 la primera transición
        tl.to(slides[i - 1], {
            opacity: 0,
            scale: 0.96,
            y: -20,
            duration: 1,
            ease: 'power2.inOut',
        }, pos)
        .fromTo(slides[i], {
            opacity: 0,
            scale: 1.04,
            y: 30,
        }, {
            opacity: 1,
            scale: 1,
            y: 0,
            duration: 1,
            ease: 'power2.out',
        }, pos);
    }

    // Click en dots = scroll a la posición de ese slide.
    // Timeline ahora tiene `total - 1` transiciones, así que el slide N está en t=N
    // y su progress = N / (total - 1).
    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.dataset.index, 10);
            const trigger = tl.scrollTrigger;
            if (!trigger) return;
            const targetProgress = total > 1 ? idx / (total - 1) : 0;
            const start = trigger.start;
            const end   = trigger.end;
            const targetY = start + (end - start) * targetProgress;
            window.scrollTo({ top: targetY, behavior: 'smooth' });
        });
    });

    // Cleanup will-change al salir del trigger
    ScrollTrigger.create({
        trigger: section,
        start: 'top bottom',
        end:   'bottom top',
        onLeave:     () => slides.forEach(s => { s.style.willChange = 'auto'; }),
        onLeaveBack: () => slides.forEach(s => { s.style.willChange = 'auto'; }),
        onEnter:     () => slides.forEach(s => { s.style.willChange = 'opacity, transform'; }),
        onEnterBack: () => slides.forEach(s => { s.style.willChange = 'opacity, transform'; }),
    });
}

/* ─────────────────────────────────────────────────────────────────────
   MOBILE — Stack vertical sin pin, fade-in por slide
   ───────────────────────────────────────────────────────────────────── */
function initMobileStaticFade(section) {
    section.classList.add('is-mobile-mode');
    section.style.height = 'auto';

    const stage = section.querySelector('[data-services-stage]');
    if (stage) {
        stage.style.position = 'static';
        stage.style.height = 'auto';
    }

    const slides = section.querySelectorAll('[data-services-slide]');
    slides.forEach((slide) => {
        // Posicionar cada slide en flujo normal
        slide.style.position = 'relative';
        slide.style.opacity = '0';
        slide.style.transform = 'translateY(30px)';
        slide.setAttribute('aria-hidden', 'false');

        ScrollTrigger.create({
            trigger: slide,
            start: 'top 75%',
            once: true,
            onEnter: () => {
                gsap.to(slide, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: 'power3.out',
                });
            },
        });
    });

    // Ocultar progress UI y dots en mobile (CSS también pero por seguridad)
    const progress = section.querySelector('.mt-services-progress');
    const dots = section.querySelector('.mt-services-dots');
    if (progress) progress.style.display = 'none';
    if (dots) dots.style.display = 'none';
}

/* ─────────────────────────────────────────────────────────────────────
   REDUCED MOTION — todo estático, sin pin ni fades
   ───────────────────────────────────────────────────────────────────── */
function applyReducedMotionFallback(section) {
    section.classList.add('is-reduced-motion');
    section.style.height = 'auto';

    const stage = section.querySelector('[data-services-stage]');
    if (stage) {
        stage.style.position = 'static';
        stage.style.height = 'auto';
    }

    section.querySelectorAll('[data-services-slide]').forEach((slide) => {
        slide.style.position = 'relative';
        slide.style.opacity = '1';
        slide.style.transform = 'none';
        slide.setAttribute('aria-hidden', 'false');
    });

    const progress = section.querySelector('.mt-services-progress');
    const dots = section.querySelector('.mt-services-dots');
    if (progress) progress.style.display = 'none';
    if (dots) dots.style.display = 'none';
}
