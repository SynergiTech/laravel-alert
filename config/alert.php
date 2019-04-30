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

    // apply an icon by the alerts type
    // - you will need to include icon in the fields list to make this happen
    // - the icon won't be applied if you have already set one
    'icons_by_type' => [],

    // map the fields to plugin output
    'output' => [
        'sweetalert' => [
            'title' => 'title',
            'text' => 'text',
            'type' => 'type',
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
