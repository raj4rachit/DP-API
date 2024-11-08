/** @type {import('tailwindcss').Config} */
export default {
    content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
    theme: {
        extend: {
            textAlign: {
                'webkit-right': '-webkit-right',
            },
        },
    },
    plugins: [],
};
