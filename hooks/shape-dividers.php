<?php

add_filter('breakdance_shape_dividers', function ($dividers) {

    $myDivider = [
        'text' => 'My Divider',
        'value' => file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "my-divider.svg")
    ];

    $dividers[] = $myDivider;

    return $dividers;
});
