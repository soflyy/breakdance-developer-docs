```php
add_action("breakdance_after_save_document", function ($postId) {
    // the save button in Breakdance was clicked and the post was saved
});
```

Disable all Google Fonts:

```php
add_filter("breakdance_register_font", function ($font) {
    $isGoogleFont = !!$font['dependencies']['googleFonts'];

    if ($isGoogleFont) {
        return false;
    }

    return $font;
});
```

```php
add_filter("breakdance_append_dependencies", function ($dependenciesToAppend) {
    // you could use this to modify or remove dependencies
    echo "<pre>";
    print_r($dependenciesToAppend);
    echo "</pre>";
    return $dependenciesToAppend;
});
```
