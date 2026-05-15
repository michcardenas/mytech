import '../../css/home.css';
import { initLenis }            from './lenis.js';
import { initScrollAnimations } from './scroll-animations.js';
import { initMarquee }          from './marquee.js';
import { initPinServicios }     from './pin-servicios.js';
import { initProcessPin }       from './process-pin.js';

function boot() {
    initLenis();
    initScrollAnimations();
    initMarquee();
    initPinServicios();
    initProcessPin();

    // Navbar scroll effect
    const navbar = document.querySelector('[data-home-navbar]');
    if (navbar) {
        const onScroll = () => {
            if (window.scrollY > 30) navbar.classList.add('is-scrolled');
            else navbar.classList.remove('is-scrolled');
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
