/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/usernotnull/tall-toasts/config/**/*.php', // Tall Toasts vendor config
    './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php', // Tall Toasts views
  './resources/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php', // 👈
   
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
