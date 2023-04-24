## breakdance_singular_content

```php
add_filter("breakdance_singular_content", function ($content) {
    if ($something) {
        return $content;
    } else {
        return "not authorized";
    }
});
```

## breakdance_after_save_document

```php
add_action("breakdance_after_save_document", function ($postId) {
    // the save button in Breakdance was clicked and the post was saved
});
```

## breakdance_register_font

```php
add_filter("breakdance_register_font", function ($font) {

    // disable all Google Fonts
    $isGoogleFont = !!$font['dependencies']['googleFonts'];
    
    if ($isGoogleFont) {
        return false;
    }

    return $font;
});
```

## breakdance_append_dependencies
```php
add_filter("breakdance_append_dependencies", function ($dependenciesToAppend) {
    // you could use this to modify or remove dependencies
    echo "<pre>";
    print_r($dependenciesToAppend);
    echo "</pre>";
    return $dependenciesToAppend;
});
```


## breakdance_element_classnames_for_html_class_attribute
```php
add_filter("breakdance_element_classnames_for_html_class_attribute", function ($classNames) {
    $classNames[] = 'another-class';
    return $classNames;
});
```

## breakdance_query_builder_input_query

The example below is copied from the Breakdance core code:

```php
add_filter('breakdance_query_builder_input_query', '\Breakdance\Integrations\FacetWp\enableFacetWpForCustomQueries');

function enableFacetWpForCustomQueries($query)
{
  if (is_string($query)) {
    return $query . "&facetwp=true";
  } else {
    $query['facetwp'] = true;
    return $query;
  }
}
```
