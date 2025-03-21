/**
 * First, we will load all of this project's JavaScript dependencies which
 * includes other libraries.
 */

import './bootstrap';
import Swal from 'sweetalert2';
import Calendar from "@toast-ui/calendar";
import "@toast-ui/calendar/dist/toastui-calendar.min.css";
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'

Alpine.plugin(ToastComponent)

Livewire.start()

// Make Swal available globally
window.Swal = Swal;

// Global state management for the sidebar (using Alpine.js from Livewire)
document.addEventListener('alpine:init', () => {
    Alpine.store('layout', {
        isSidebarOpen: true,
        isMobileMenuOpen: false,
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen;
        }
    });
});
