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
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            // MENAMBAHKAN WARNA WATU KE SYSTEM TAILWIND
            colors: {
                'watu-olive': '#5f674d',       // Hijau Olive
                'watu-olive-dark': '#424836',  // Olive Gelap
                'watu-cream': '#F9F7F2',       // Cream Background
                'watu-dark': '#2b2623',        // Hitam Kecoklatan (Teks)
                'watu-gold': '#d4a056',        // Emas/Kuning
            }
        },
    },

    plugins: [forms],
};