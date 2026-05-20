import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /blog/{slug} — Detalle del blog post premium.
 *
 * Guarded por [data-bd-hero]. Animaciones:
 *   1. Char-stagger del título
 *   2. Reading progress bar sticky
 *   3. Featured image parallax suave
 *   4. TOC auto-generado desde h2/h3 + scroll spy
 *   5. Copy link → toast
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initBlogDetalleStorytelling() {
    if (! document.querySelector('[data-bd-hero]')) return;

    initTitleStagger();
    initReadingProgress();
    initFeaturedParallax();
    initTOC();
    initCopyLink();
}

/* ── 1. Char-stagger del título ─────────────────────── */
function initTitleStagger() {
    if (reducedMotion()) return;
    const title = document.querySelector('[data-bd-title]');
    if (! title) return;

    // Split words first (no chars — para titles largos de blog mejor word stagger)
    const text = title.textContent;
    title.innerHTML = text.split(' ').map(word =>
        `<span class="word" style="display:inline-block; will-change: transform, opacity;">${word}</span>`
    ).join(' ');

    const words = title.querySelectorAll('.word');
    gsap.from(words, {
        opacity: 0,
        y: 28,
        rotateX: -20,
        stagger: 0.035,
        duration: 0.95,
        ease: 'power3.out',
        delay: 0.1,
    });
}

/* ── 2. Reading progress bar ────────────────────────── */
function initReadingProgress() {
    const fill = document.querySelector('[data-bd-progress]');
    const content = document.querySelector('[data-bd-content]');
    if (! fill || ! content) return;

    function update() {
        const rect = content.getBoundingClientRect();
        const docTop = window.scrollY;
        const contentTop = rect.top + docTop;
        const contentEnd = contentTop + content.offsetHeight - window.innerHeight;
        const progress = Math.max(0, Math.min(1, (docTop - contentTop) / (contentEnd - contentTop)));
        fill.style.transform = `scaleX(${progress})`;
    }
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
}

/* ── 3. Featured image parallax ─────────────────────── */
function initFeaturedParallax() {
    if (reducedMotion()) return;
    const img = document.querySelector('[data-bd-featured-image] img');
    if (! img) return;

    gsap.to(img, {
        yPercent: 12,
        ease: 'none',
        scrollTrigger: {
            trigger: '[data-bd-featured-image]',
            start: 'top bottom',
            end: 'bottom top',
            scrub: true,
        },
    });
}

/* ── 4. TOC auto-generado + scroll spy ──────────────── */
function initTOC() {
    const tocList = document.querySelector('[data-bd-toc-list]');
    const content = document.querySelector('[data-bd-content]');
    if (! tocList || ! content) return;

    // Detectar h2 y h3 del contenido (lo que Quill genera)
    const headings = content.querySelectorAll('h2, h3');
    if (headings.length === 0) {
        // Ocultar TOC entero si no hay headings
        const toc = document.querySelector('[data-bd-toc]');
        if (toc) toc.style.display = 'none';
        return;
    }

    // Generar ID slug a cada heading si no tiene
    const slugify = (s) => (s || '')
        .toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-');

    const items = [];
    headings.forEach((h, i) => {
        const text = h.textContent.trim();
        let id = h.id;
        if (! id) {
            id = slugify(text) || `heading-${i}`;
            h.id = id;
        }
        items.push({ id, text, level: h.tagName.toLowerCase() });
    });

    // Construir items
    tocList.innerHTML = items.map(it =>
        `<li class="mt-bd-toc-item mt-bd-toc-${it.level}" data-bd-toc-item data-target="${it.id}">
            <a href="#${it.id}">${it.text}</a>
        </li>`
    ).join('');

    // Scroll spy con IntersectionObserver
    const tocItems = tocList.querySelectorAll('[data-bd-toc-item]');
    const tocByTarget = new Map();
    tocItems.forEach(it => tocByTarget.set(it.dataset.target, it));

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const id = entry.target.id;
            const tocItem = tocByTarget.get(id);
            if (! tocItem) return;
            if (entry.isIntersecting) {
                tocItems.forEach(i => i.classList.remove('is-active'));
                tocItem.classList.add('is-active');
            }
        });
    }, { rootMargin: '-30% 0px -65% 0px', threshold: 0 });
    headings.forEach(h => obs.observe(h));

    // Smooth scroll al hacer click (sin saltar)
    tocList.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', (e) => {
            e.preventDefault();
            const id = a.getAttribute('href').slice(1);
            const target = document.getElementById(id);
            if (! target) return;
            const offset = 100;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        });
    });
}

/* ── 5. Copy link with toast ────────────────────────── */
function initCopyLink() {
    const buttons = document.querySelectorAll('[data-bd-copy-link]');
    const toast = document.querySelector('[data-bd-toast]');
    if (buttons.length === 0) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.url || window.location.href;
            try {
                await navigator.clipboard.writeText(url);
                if (toast) {
                    toast.classList.add('is-visible');
                    toast.setAttribute('aria-hidden', 'false');
                    setTimeout(() => {
                        toast.classList.remove('is-visible');
                        toast.setAttribute('aria-hidden', 'true');
                    }, 2200);
                }
            } catch (err) {
                console.error('Copy failed', err);
            }
        });
    });
}
