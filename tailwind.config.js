/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#0C2B4E',      // Deep Navy - Primary color
        'secondary': '#1A3D64',    // Navy Blue - Secondary
        'accent': '#1D546C',       // Teal Blue - Accent
        'light': '#F4F4F4',        // Light Gray - Background
        'muted': '#5D688A',        // Slate - Muted text

        // Alias untuk backward compatibility
        'navy': '#0C2B4E',
        'blue-light': '#1A3D64',
      },
    },
  },
  plugins: [],
}
