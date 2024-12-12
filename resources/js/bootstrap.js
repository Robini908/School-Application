// Importing necessary libraries
import _ from 'lodash';
// import Popper from 'popper.js';
import $ from 'jquery';
// import 'bootstrap';
import axios from 'axios';

/**
 * Make jQuery, Popper, and Lodash globally available
 */
window._ = _;
window.Popper = Popper;
// window.$ = window.jQuery = $;

// Ensure jQuery is ready
// $(function() {
//     console.log('jQuery is ready!');
    
//     // Additional jQuery code can go here
//     // For example, setting up Bootstrap modals or event listeners
// });

/**
 * Set up Axios for HTTP requests
 */
window.axios = axios;

// Set the default Axios headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Register the CSRF Token as a common header with Axios
 */
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Optional: Uncomment the following code to set up Laravel Echo
 */

// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
