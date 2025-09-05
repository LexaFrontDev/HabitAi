/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './assets/react/**/*.{js,jsx,ts,tsx}',
        './assets/styles/**/*.{css,scss}',
        './templates/**/*.html.twig',
    ],
    theme: {
        extend: {
            fontFamily: {
                klein: ['Klein', 'sans-serif'],
                poppins: ['Poppins', 'sans-serif'],
            },
        },
    },
    safelist: [
        'text-red-500',
        'font-klein',
    ],
    plugins: [],
}
