import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initPinServicios() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.innerWidth < 1024) return; // En mobile el pin se desactiva (mejor experiencia)

    const section  = document.querySelector('[data-pin-servicios]');
    const sticky   = document.querySelector('[data-pin-servicios-sticky]');
    const cards    = document.querySelectorAll('[data-pin-servicios-card]');
    if (!section || !sticky || cards.length === 0) return;

    // Estado inicial: todas las cards invisibles excepto la primera
    gsap.set(cards, { opacity: 0.15, y: 0 });
    gsap.set(cards[0], { opacity: 1 });

    cards.forEach((card, i) => {
        ScrollTrigger.create({
            trigger: card,
            start: 'top 60%',
            end:   'top 40%',
            onEnter:     () => gsap.to(card, { opacity: 1,    duration: 0.5 }),
            onLeave:     () => gsap.to(card, { opacity: 0.25, duration: 0.5 }),
            onEnterBack: () => gsap.to(card, { opacity: 1,    duration: 0.5 }),
            onLeaveBack: () => gsap.to(card, { opacity: 0.15, duration: 0.5 }),
        });
    });
}
