/**
 * First, we will load all of this project's JavaScript dependencies,
 * which includes Alpine.js, Livewire, and other libraries.
 */

import './bootstrap';


import '../../vendor/masmerise/livewire-toaster/resources/js'; // 

import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'

Alpine.plugin(ToastComponent)
