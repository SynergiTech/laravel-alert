<?php

/**
 * Configuration options for synergitech/laravel-alert
 */
return [
    // fields and their defaults but make sure
    // to mention all the fields you want in the output
    // - you may experience issues if you don't include title, text, and type
    'fields' => [
        'title' => '',
        'text' => '',
        'type' => '',
    ],

    // Support multiple popup/alert plugins by adding them to the output list.
    // You can specify which fields to output (values) and what object key (keys)
    // they should be named for each plugin. The default sweetalert config works
    // for v9+. Older sweetalert versions should use `type` instead of `icon`.
    'output' => [
        'sweetalert' => [
            'title' => 'title',
            'text' => 'text',
            'icon' => 'type',
        ],
    ],

    // types for the plugin
    // - this is useful for quickly applying all the properties
    'types' => [
        'info',
        'success',
        'error',
        'warning',
    ],
];
