/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [ 
    "./pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "./**/*.php",
    "./**/*.html",
  ],


  theme: {container: {
      center: true,
      padding: "15px",

    },
    fontFamily: {
      primary: "var(--font-cormorant_upright)",
      secondary: "var(--font-open_sans)",
      tertiary: ['Quinoa', 'san-serif'],
    },


    extend: {
      colors: {
        primary: {
          DEFAULT: "#100e0e",
        },
        secondary: {
          DEFAULT: "#787f8a",
        },
        accent: {
          DEFAULT: "#c7a17a",
          hover: "#a08161",
        },
        navFont: {
          DEFAULT: "black",
          hover: " rgb(235, 139, 29)",
        },
        Menu: {
          DEFAULT: "#101828",
        },
        dark: '#2c3e50',
        darker: '#1a2533',
         cream: '#cdb496'
      },

      backgroundImage: {
        product_bg: "url('../src/assets/productbg.jpg')",
        hero_overlay: "url('../src/assets/hero/hero-overlay.png')",
        opening_hours: "url('src/assets/opening-hours/bg.png')",
        footer: "url('src/assets/footer/bg.jpg')",
        about_bg: "url('../src/assets/aboutBG.jpg')",
      },
    },

  },
  plugins: [],
}

