import '../../css/home.css';

// Alpine.js — necesario para x-data/x-show/@click del navbar (menú mobile)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { initLenis }              from './lenis.js';
import { initScrollAnimations }   from './scroll-animations.js';
import { initMarquee }            from './marquee.js';
import { initPinServicios }       from './pin-servicios.js';
import { initProcessPin }         from './process-pin.js';
import { initScrollStorytelling }            from './scroll-storytelling.js';
import { initServicesStorytelling }          from './services-storytelling.js';
import { initProyectosStorytelling }         from './proyectos-storytelling.js';
import { initProyectoDetalleStorytelling }   from './proyecto-detalle-storytelling.js';
import { initBlogStorytelling }              from './blog-storytelling.js';
import { initBlogDetalleStorytelling }       from './blog-detalle-storytelling.js';
import { initContacto }                      from './contacto.js';
import { initSobreNosotros }                 from './sobre-nosotros.js';
import { initHeroVideo }                     from './hero-video.js';

function boot() {
    initLenis();
    initScrollAnimations();
    initMarquee();
    initPinServicios();
    initProcessPin();
    initScrollStorytelling();
    initServicesStorytelling();          // guarded — solo en /servicios
    initProyectosStorytelling();         // guarded — solo en /proyectos
    initProyectoDetalleStorytelling();   // guarded — solo en /proyectos/{slug}
    initBlogStorytelling();              // guarded — solo en /blog
    initBlogDetalleStorytelling();       // guarded — solo en /blog/{slug}
    initContacto();                      // guarded — solo en /contacto
    initSobreNosotros();                 // guarded — solo en /sobre-nosotros
    initHeroVideo();                     // lazy load del video hero (desktop only, post-LCP)

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
