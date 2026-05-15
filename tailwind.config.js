import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Paleta home — alineada al logo (azul + gris oscuro)
                'mt-bg':       '#FFFFFF',
                'mt-bg-2':     '#F9FAFB', // gris muy claro
                'mt-bg-3':     '#F3F4F6',
                'mt-bg-dark':  '#0B1220', // azul-noche profundo

                'mt-text':         '#1F2937', // gris oscuro del logo "Tech Solutions"
                'mt-text-2':       '#4B5563', // gris medio
                'mt-text-3':       '#9CA3AF', // gris claro
                'mt-text-inv':     '#FFFFFF',
                'mt-text-on-dark': '#CBD5E1', // texto secundario sobre bg-dark

                'mt-accent':         '#2563EB', // azul del logo
                'mt-accent-hover':   '#1D4ED8',
                'mt-accent-dark':    '#1E40AF',
                'mt-accent-soft':    '#EFF6FF', // bg suave para badges
                'mt-accent-line':    '#DBEAFE', // borde suave
                'mt-accent-on-dark': '#60A5FA', // acento sobre bg-dark

                'mt-border':   '#E5E7EB',
                'mt-border-2': '#D1D5DB',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Inter Tight"', 'Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
                figtree: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                // Tipografía un poco más calmada (line-height más relajado, weight 700 no 800)
                'hero':           ['clamp(2.75rem, 7vw, 6rem)',     { lineHeight: '1.02', letterSpacing: '-0.03em',  fontWeight: '700' }],
                'section':        ['clamp(2rem, 4.8vw, 4rem)',      { lineHeight: '1.1',  letterSpacing: '-0.022em', fontWeight: '700' }],
                'metric':         ['clamp(3rem, 9vw, 7.5rem)',      { lineHeight: '0.95', letterSpacing: '-0.03em',  fontWeight: '700' }],
                'footer-display': ['clamp(2.75rem, 12vw, 11rem)',   { lineHeight: '0.88', letterSpacing: '-0.04em',  fontWeight: '700' }],
            },
            animation: {
                'marquee':         'marquee 60s linear infinite',
                'marquee-reverse': 'marquee-reverse 65s linear infinite',
                'pulse-soft':      'pulse-soft 4s ease-in-out infinite',
            },
            keyframes: {
                'marquee': {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'marquee-reverse': {
                    '0%': { transform: 'translateX(-50%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                'pulse-soft': {
                    '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                    '50%':      { opacity: '0.7', transform: 'scale(1.08)' },
                },
            },
            boxShadow: {
                'mt-soft':   '0 2px 14px rgba(37, 99, 235, 0.06)',
                'mt-medium': '0 8px 28px rgba(37, 99, 235, 0.08)',
                'mt-strong': '0 16px 48px rgba(37, 99, 235, 0.12)',
                'mt-btn':    '0 4px 14px rgba(37, 99, 235, 0.25)',
                'mt-btn-h':  '0 10px 30px rgba(37, 99, 235, 0.35)',
            },
        },
    },

    plugins: [forms],
};
