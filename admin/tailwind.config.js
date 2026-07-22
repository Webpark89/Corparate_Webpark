module.exports = {
  content: [
    './**/*.php',
    './admin/**/*.php',
    './admin/assets/js/**/*.js'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Noto Sans Thai", "Inter", "ui-sans-serif", "system-ui", "-apple-system", "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "sans-serif"]
      },
      colors: {
        primary: '#2563eb',
        'primary-600': '#1d4ed8',
        'primary-blue': '#163172',
        'accent-cyan': '#48cae4',
        'bg-midnight': '#050c1f',
        'bg-navy': '#163172',
        'bg': '#f6f8fb',
        'bg-light': '#f4f7fa',
        surface: '#ffffff',
        'card-bg': '#ffffff',
        'text-main': '#212529',
        'text-muted': '#6c757d',
        danger: '#ef4444',
        'danger-600': '#dc3545',
        success: '#28a745',
        warning: '#ffc107',
      },
      spacing: {
        xs: '0.25rem',
        sm: '0.5rem',
        md: '1rem',
        lg: '1.5rem',
        xl: '2rem',
        '2xl': '2.5rem',
        container: '1100px',
        'sidebar-width': '260px'
      },
      container: {
        center: true,
        padding: '1rem',
        screens: {
          lg: '1100px'
        }
      },
      borderRadius: {
        sm: '6px',
        md: '8px',
        lg: '10px',
        xl: '12px',
        '2xl': '20px'
      },
      boxShadow: {
        sm: '0 2px 4px rgba(0, 0, 0, 0.02)',
        md: '0 4px 12px rgba(0, 0, 0, 0.05)',
        lg: '0 6px 20px rgba(16, 24, 40, 0.06)'
      }
      ,
      keyframes: {
        lift: {
          '0%': { transform: 'translateY(0)' },
          '100%': { transform: 'translateY(-3px)' }
        },
        'fade-in': {
          from: { opacity: '0' },
          to: { opacity: '1' }
        },
        'slide-up': {
          from: { transform: 'translateY(10px)', opacity: '0' },
          to: { transform: 'translateY(0)', opacity: '1' }
        }
      },
      animation: {
        lift: 'lift 0.18s ease',
        'fade-in': 'fade-in 180ms ease',
        'slide-up': 'slide-up 220ms ease'
      }
    }
  },
  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')]
}
