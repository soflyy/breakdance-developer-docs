# breakdance_form_validate_field

The `breakdance_form_validate_field` filter hook allows third-party developers to add custom validation logic to Breakdance form fields. This hook is called for each field during form submission validation, enabling you to implement custom validation rules beyond the built-in validation.

**Note**: Requires Breakdance 2.8+.

## Location

This hook is located in the `validateFormData()` function in:
```
/plugin/forms/custom/custom.php
```

## Hook Type

**Filter**

## Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldErrors` | `\WP_Error` | A WP_Error object where you can add validation errors. This is a fresh error bag for each field. |
| `$field` | `array` | The field being validated. Contains field data including `type`, `value`, `label`, `advanced`, `originalValue`, etc. |
| `$formId` | `int` | The ID of the form being submitted. |
| `$postId` | `int` | The ID of the post/page containing the form. |

## Return Value

**Type:** `\WP_Error`

Return the `$fieldErrors` object. If you added any errors to it, they will be included in the form validation results.

## Field Array Structure

The `$field` parameter contains:

```php
[
    'type' => 'text|email|tel|textarea|file|etc',
    'value' => 'sanitized user submitted value',
    'originalValue' => 'original submitted value (may be array)',
    'label' => 'Field Label',
    'advanced' => [
        'id' => 'field_id',
        'required' => true|false,
        'conditional' => true|false,
        // ... other advanced settings
    ],
    // ... other field properties based on field type
]
```

## Usage Examples

### Basic Validation

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    // Validate phone number format
    if ($field['type'] === 'tel' && !empty($field['value'])) {
        if (!preg_match('/^\d{10}$/', $field['value'])) {
            $fieldErrors->add(
                'invalid_phone',
                'Please enter a valid 10-digit phone number.'
            );
        }
    }

    return $fieldErrors;
}, 10, 4);
```

### Validate Specific Field by ID

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    $fieldId = $field['advanced']['id'] ?? '';

    // Validate a specific field
    if ($fieldId === 'custom_zip_code' && !empty($field['value'])) {
        if (!preg_match('/^\d{5}(-\d{4})?$/', $field['value'])) {
            $fieldErrors->add(
                'invalid_zip',
                'Please enter a valid ZIP code (12345 or 12345-6789).'
            );
        }
    }

    return $fieldErrors;
}, 10, 4);
```

### Validate Based on Form ID

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    // Only validate for specific forms
    if ($formId === 123) {
        $fieldId = $field['advanced']['id'] ?? '';

        if ($fieldId === 'age' && !empty($field['value'])) {
            $age = intval($field['value']);
            if ($age < 18) {
                $fieldErrors->add(
                    'age_restriction',
                    'You must be 18 or older to submit this form.'
                );
            }
        }
    }

    return $fieldErrors;
}, 10, 4);
```

### Advanced Validation with Multiple Conditions

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    $fieldId = $field['advanced']['id'] ?? '';

    // Custom email domain validation
    if ($field['type'] === 'email' && !empty($field['value'])) {
        $allowedDomains = ['company.com', 'example.org'];
        $email = $field['value'];
        $domain = substr(strrchr($email, "@"), 1);

        if (!in_array($domain, $allowedDomains)) {
            $fieldErrors->add(
                'invalid_domain',
                sprintf(
                    'Email must be from one of these domains: %s',
                    implode(', ', $allowedDomains)
                )
            );
        }
    }

    // Password strength validation
    if ($fieldId === 'password' && !empty($field['value'])) {
        $password = $field['value'];

        if (strlen($password) < 8) {
            $fieldErrors->add('password_length', 'Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $fieldErrors->add('password_uppercase', 'Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            $fieldErrors->add('password_number', 'Password must contain at least one number.');
        }
    }

    return $fieldErrors;
}, 10, 4);
```

### API Validation

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    $fieldId = $field['advanced']['id'] ?? '';

    // Validate coupon code against external API
    if ($fieldId === 'coupon_code' && !empty($field['value'])) {
        $couponCode = $field['value'];

        // Call external API to validate coupon
        $response = wp_remote_get("https://api.example.com/validate-coupon?code={$couponCode}");

        if (is_wp_error($response)) {
            $fieldErrors->add('coupon_error', 'Unable to validate coupon code. Please try again.');
        } else {
            $body = json_decode(wp_remote_retrieve_body($response), true);

            if (!$body['valid']) {
                $fieldErrors->add('invalid_coupon', 'This coupon code is not valid.');
            }
        }
    }

    return $fieldErrors;
}, 10, 4);
```

### Database Validation

```php
add_filter('breakdance_form_validate_field', function($fieldErrors, $field, $formId, $postId) {
    $fieldId = $field['advanced']['id'] ?? '';

    // Check if username already exists
    if ($fieldId === 'username' && !empty($field['value'])) {
        $username = sanitize_user($field['value']);

        if (username_exists($username)) {
            $fieldErrors->add('username_exists', 'This username is already taken.');
        }
    }

    // Check if email already exists
    if ($field['type'] === 'email' && $fieldId === 'user_email' && !empty($field['value'])) {
        if (email_exists($field['value'])) {
            $fieldErrors->add('email_exists', 'An account with this email already exists.');
        }
    }

    return $fieldErrors;
}, 10, 4);
```

## Best Practices

### 1. Always Check Field Type or ID

Only validate fields that are relevant to your custom validation logic:

```php
if ($field['type'] === 'email' && !empty($field['value'])) {
    // Your validation logic
}
```

### 2. Check for Empty Values

Don't validate empty values unless you need to enforce a custom required field rule:

```php
if (!empty($field['value'])) {
    // Your validation logic
}
```

### 3. Return the Error Object

Always return the `$fieldErrors` object, even if you didn't add any errors:

```php
return $fieldErrors;
```

### 4. Use Descriptive Error Codes and Messages

Make error codes unique and messages user-friendly:

```php
$fieldErrors->add('invalid_phone_format', 'Phone number must be in format: (123) 456-7890');
```

### 5. Consider Performance

Avoid heavy operations (like API calls) unless necessary. Consider caching results:

```php
$cacheKey = 'validated_' . md5($field['value']);
$cached = get_transient($cacheKey);

if ($cached !== false) {
    if (!$cached) {
        $fieldErrors->add('validation_error', 'Invalid value.');
    }
} else {
    // Perform expensive validation
    $isValid = expensive_validation($field['value']);
    set_transient($cacheKey, $isValid, HOUR_IN_SECONDS);

    if (!$isValid) {
        $fieldErrors->add('validation_error', 'Invalid value.');
    }
}
```

### 6. Sanitize User Input

Always sanitize field values before using them in comparisons or database queries:

```php
$fieldValue = sanitize_text_field($field['value']);
```

### 7. Use Priority Wisely

Set appropriate priority if your validation depends on other validations:

```php
// Run after other validations
add_filter('breakdance_form_validate_field', 'my_validation', 20, 4);

// Run before other validations
add_filter('breakdance_form_validate_field', 'my_validation', 5, 4);
```

## Hook Execution Flow

1. Form is submitted
2. For each field in the form:
   - A fresh `WP_Error` object is created
   - The `breakdance_form_validate_field` filter is called
   - All hooked functions receive the same error object
   - Functions can add errors using `$fieldErrors->add()`
   - If the returned error object has errors, they are merged into the main validation bag
3. If the main bag has errors, form submission fails and errors are displayed
4. If no errors, form submission continues

## Error Display

Errors added through this hook will be displayed to users along with other form validation errors. The exact display depends on the form's error message settings.

## Related Hooks

- `breakdance_form_run_action_{action_slug}` - Control whether a specific form action should run
- `breakdance_form_honeypot_triggered` - Triggered when honeypot spam protection is triggered

## Support

For more information about Breakdance forms and available hooks, visit the [Breakdance Documentation](https://breakdance.com/documentation/).

