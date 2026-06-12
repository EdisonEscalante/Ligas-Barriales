/** @type {import('tailwindcss').Config} */
module.exports = {
  // Indicamos dónde están las vistas para que Tailwind genere solo los estilos necesarios
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}