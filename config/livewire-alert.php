<?php

/*
 * For more details about the configuration, see:
 * https://sweetalert2.github.io/#configuration
 */
return [
    'alert' => [
        'position' => 'top-end',
        'timer' => 3000,
        'toast' => true,
        'text' => null,
        'showCancelButton' => false,
        'showConfirmButton' => false
    ],
    'confirm' => [
        'icon' => 'warning',
        'position' => 'center',
        'toast' => false,
        'timer' => null,
        'showConfirmButton' => true,
        'showCancelButton' => true,
        'cancelButtonText' => 'No',
        'confirmButtonColor' => '#3085d6',
        'cancelButtonColor' => '#d33'
    ],
    'toast_with_progress' => [
        'position' => 'top-end',
        'timer' => 5000,
        'toast' => true,
        'text' => null,
        'showCancelButton' => false,
        'showConfirmButton' => false,
        'timerProgressBar' => true, // Enable progress bar
        'didOpen' => 'toastOpened', // JavaScript hook when toast opens
        'willClose' => 'toastClosed', // JavaScript hook when toast closes
        'icon' => 'info', // Default icon for toast with progress
    ],
];
