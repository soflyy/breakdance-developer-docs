## breakdance_form_start
```php
add_action('breakdance_form_start', function ($settings) {
    echo "<div class=\"breakdance-form-group\">
        <h4 style='margin: 0;'>{$settings['form']['form_name']}</h4>
    </div>";
});
```

## breakdance_form_before_field
```php
add_action('breakdance_form_before_field', function ($field, $settings) {
    echo 'The field below is ' . ($field['advanced']['required'] ? '' : 'not') . ' required.';
}, 10, 2);
```

## breakdance_form_after_field
```php
add_action('breakdance_form_after_field', function ($field, $settings) {
    echo "<span>Type: <strong>{$field['type']}</strong> / ID: {$field['advanced']['id']}</span>";
}, 10, 2);
```

## breakdance_form_before_footer
```php
add_action('breakdance_form_before_footer', function ($settings) {
    echo '
    <div class="breakdance-form-group">
        <button>AutoFill with LinkedIn</button>
    </div>
    ';
});
```

## breakdance_form_end
```php
add_action('breakdance_form_end', function ($settings) {
    echo '<div class="breakdance-form-group">By submitting this form you agree to the terms of service.</div>';
});
```