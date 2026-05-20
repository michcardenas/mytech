import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const EASE = 'power3.out';      // easing más natural y profesional
const DURATION = 1.1;
const STAGGER = 0.09;

export function initScrollAnimations() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-animate]').forEach(el => el.classList.add('is-visible'));
        return;
    }

    // Fade-ins genéricos
    document.querySelectorAll('[data-animate]').forEach((el) => {
        gsap.fromTo(el,
            { opacity: 0, y: 28 },
            {
                opacity: 1,
                y: 0,
                duration: DURATION,
                ease: EASE,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                    toggleActions: 'play none none none',
                },
                onComplete: () => el.classList.add('is-visible'),
            }
        );
    });

    // Stagger de hijos
    document.querySelectorAll('[data-animate-children]').forEach((parent) => {
        const children = parent.children;
        gsap.fromTo(children,
            { opacity: 0, y: 22 },
            {
                opacity: 1,
                y: 0,
                duration: 0.85,
                ease: EASE,
                stagger: STAGGER,
                scrollTrigger: {
                    trigger: parent,
                    start: 'top 82%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    // Counters
    document.querySelectorAll('[data-counter]').forEach((el) => {
        const target = parseFloat(el.dataset.counter || '0');
        const decimals = parseInt(el.dataset.counterDecimals || '0', 10);
        const obj = { val: 0 };
        gsap.to(obj, {
            val: target,
            duration: 2.4,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 82%',
                once: true,
            },
            onUpdate: () => {
                el.textContent = obj.val.toFixed(decimals);
            },
        });
    });
}
