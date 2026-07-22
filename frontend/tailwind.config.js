/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/**/*.php',
    './public/**/*.php',
    './src/styles/**/*.css',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Noto Sans Thai', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      boxShadow: {
        soft: '0 8px 30px rgba(1, 29, 81, 0.06)',
        card: '0 12px 40px rgba(1, 29, 81, 0.1)',
      },
      colors: {
        primary: '#0663F6',
        dark: '#001f5c',
        brand: {
          navy: '#001f5c',
          blue: '#043e89',
          teal: '#0fb0c6',
          cyan: '#00B4D8',
        },
      },
      fontSize: {
        '2xs': ['11px', { lineHeight: '1.5' }],
        '3xs': ['12px', { lineHeight: '1.5' }],
        '3.5': ['13.5px', { lineHeight: '1.5' }],
      },
      minHeight: {
        'card-sm': '65px',
        'card-md': '160px',
        'card-lg': '220px',
      },
      letterSpacing: {
        tighter: '-0.02em',
        widest: '0.15em',
      },
      lineHeight: {
        tighter: '1.3',
        relaxed: '1.625',
        relaxeds: '1.625',
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
};
