import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Sección "Cómo trabajamos" — pin scroll horizontal.
 *
 * Funciona IGUAL en desktop y mobile: mientras scrolleas vertical,
 * las cards se desplazan horizontal. En mobile las cards son más pequeñas
 * para que se aprecien bien.
 */
export function initProcessPin() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const section  = document.querySelector('[data-process-section]');
    const pin      = section?.querySelector('[data-process-pin]');
    const track    = section?.querySelector('[data-process-track]');
    const steps    = section?.querySelectorAll('[data-process-step]');
    const fillEl   = section?.querySelector('[data-process-progress]');
    const labelEl  = section?.querySelector('[data-process-progress-label]');

    if (!section || !pin || !track || !steps || steps.length === 0) return;

    const updateProgress = (idx) => {
        const total = steps.length;
        const pct = total > 1 ? (idx / (total - 1)) * 100 : 0;
        if (fillEl)  fillEl.style.width = pct.toFixed(1) + '%';
        if (labelEl) labelEl.textContent = `${String(idx + 1).padStart(2, '0')} / ${String(total).padStart(2, '0')}`;
    };

    const setActiveStep = (idx) => {
        steps.forEach((s, i) => {
            s.classList.toggle('is-active', i === idx);
        });
        updateProgress(idx);
    };

    let st = null;
    let tween = null;

    const computeDistance = () => {
        const trackWidth = track.scrollWidth;
        const visible    = track.parentElement.offsetWidth;
        return Math.max(0, trackWidth - visible);
    };

    const buildScroll = () => {
        // Limpiar instancia previa si existe (resize)
        if (st) { st.kill(); st = null; }
        if (tween) { tween.kill(); tween = null; }
        gsap.set(track, { clearProps: 'transform' });

        // Si solo hay 1 card no tiene sentido el pin
        if (steps.length < 2) return;

        tween = gsap.to(track, {
            x: () => -computeDistance(),
            ease: 'none',
        });

        st = ScrollTrigger.create({
            trigger: section,
            start: 'top top',
            end: () => '+=' + (section.offsetHeight - window.innerHeight),
            pin: pin,
            scrub: 0.8,
            anticipatePin: 1,
            invalidateOnRefresh: true,
            animation: tween,
            onUpdate: (self) => {
                const total = steps.length;
                const idx = Math.min(total - 1, Math.floor(self.progress * total));
                setActiveStep(idx);
            },
        });
    };

    buildScroll();

    // Refrescar en resize / orientation change
    let resizeTimer = null;
    const onResize = () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            buildScroll();
            ScrollTrigger.refresh();
        }, 220);
    };
    window.addEventListener('resize', onResize);
    window.addEventListener('orientationchange', onResize);
}
