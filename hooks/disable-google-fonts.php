<?php

add_filter("breakdance_register_font", function ($font) {
    $isGoogleFont = !!$font['dependencies']['googleFonts'];

    if ($isGoogleFont) {
        return false;
    }

    return $font;
});
