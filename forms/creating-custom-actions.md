# Form Actions API

Creating a Breakdance Form Action involves two steps

1. Create an Action class to represent your field
2. Register the action class with Breakdance

# Creating an action class

To get started create a new PHP class. Your class will need to extend the Breakdance base Action class `Breakdance\Forms\Actions\Action`

## Required Methods

There are three mandatory methods that must be implemented in your action class

**name**

The name method takes no arguments and returns a string that will be used to identify your action in the Form Builder Actions dropdown menu

```php
/**
 * @return string
 */
public function name() {
	return 'My Action';
}
```

**slug**

The slug method takes no arguments and returns a string to identify the form action. This should be unique across all form actions, so it is recommended to prefix the slug appropriately.

```php
/**
 * @return string
*/
public function slug()
{
    return 'my_plugin_form_action';
}
```

**run**

The run method accepts three arguments,  `$form, $settings, $extra` and is called when the form has been submitted

```php
/**
* Log the form submission to a file
*
* @param array $form
* @param array $settings
* @param array $extra
* @return array success or error message
*/
public function run($form, $settings, $extra)
{
    try {
        $this->writeToFile($extra['formId'], $extra['fields']);
    } catch(Exception $e) {
        return ['type' => 'error', 'message' => $e->getMessage()];
    }

    return ['type' => 'success', 'message' => 'Submission logged to file'];
}

```

### Run Arguments

$**form**

The form argument contains all the form fields, their builder settings and the selected values

- type: the field type
- name: the field name
- options: an array of available options for checkbox, radio or select inputs
- value: the submitted value of the field
- originalValue: the default/original value of the field

**$settings**

The settings argument contains an array of the configured form settings from the Breakdance builder

**$extra**

The extra argument contains additional data

- files: An array of uploaded files
- fields: the submitted form fields in an `$id ⇒ $value` style array
- formId: The ID of the form
- postId: The ID of the post the form was submitted from
- ip: the submitters IP address
- referer: The form submitters referrer URL
- userAgent: The form submitters user agent string
- userId: The form submitters user ID (if applicable)

### Responses

The response should be an array that contains a `type` and `message` key.

- type: either `error` or `success`
- message: a string message that will be displayed to admins with the submission

**Success**

```php
public function run($form, $settings, $extra)
{
    ...
    return ['type' => 'success', 'message' => 'Submission logged to file'];
}

```

**Error**

```php
public function run($form, $settings, $extra)
{
    ...
    return ['type' => 'error', 'message' => 'Could not write to file'];
}
```

## Register The Action

Register the action by calling the registerAction helper and passing an instance of your action class

**Note:** To prevent file loading race conditions, it is recommended to call the register helper from inside a WordPress action, e.g init.

```php

// register-actions.php included by your plugin

add_action('init', function() {
    // fail if Breakdance is not installed and available
    if (!function_exists('\Breakdance\Forms\Actions\registerAction') || !class_exists('\Breakdance\Forms\Actions\Action')) {
        return;
    }

    require_once('my-action.php');

    \Breakdance\Forms\Actions\registerAction(new MyAction());
});
```

## Full Example

Here's a complete example of a custom form action that automatically publishes recipe submissions to your blog:

```php
<?php
/**
 * Recipe Submission Action
 * Automatically publishes recipe submissions as blog posts
 */

namespace MyPlugin\Forms\Actions;

use Breakdance\Forms\Actions\Action;

class RecipeSubmissionAction extends Action
{
    /**
     * The name displayed in the Form Builder Actions dropdown
     *
     * @return string
     */
    public function name()
    {
        return 'Publish Recipe';
    }

    /**
     * Unique identifier for this action
     *
     * @return string
     */
    public function slug()
    {
        return 'publish_recipe';
    }

    /**
     * Process the form submission
     *
     * @param array $form The form fields and their values
     * @param array $settings The form settings from the builder
     * @param array $extra Additional submission data
     * @return array Response with type and message
     */
    public function run($form, $settings, $extra)
    {
        $fields = $extra['fields'];

        // Get the recipe category (create if doesn't exist)
        $category = get_term_by('slug', 'recipes', 'category');
        if (!$category) {
            $result = wp_insert_term('Recipes', 'category', ['slug' => 'recipes']);
            $category_id = is_wp_error($result) ? 1 : $result['term_id'];
        } else {
            $category_id = $category->term_id;
        }

        // Build the post content with recipe details
        $content = '';

        // Add description if available
        if (!empty($fields['description'])) {
            $content .= '<p>' . wp_kses_post($fields['description']) . '</p>';
        }

        // Add ingredients section
        if (!empty($fields['ingredients'])) {
            $content .= '<h2>Ingredients</h2>';
            $content .= '<div class="recipe-ingredients">';
            $content .= wpautop(wp_kses_post($fields['ingredients']));
            $content .= '</div>';
        }

        // Add instructions section
        if (!empty($fields['instructions'])) {
            $content .= '<h2>Instructions</h2>';
            $content .= '<div class="recipe-instructions">';
            $content .= wpautop(wp_kses_post($fields['instructions']));
            $content .= '</div>';
        }

        // Add cooking time if available
        if (!empty($fields['cooking_time'])) {
            $content .= '<p><strong>Cooking Time:</strong> ' . esc_html($fields['cooking_time']) . '</p>';
        }

        // Add submitter name to content
        if (!empty($fields['your_name'])) {
            $content .= '<p><em>Recipe submitted by: ' . esc_html($fields['your_name']) . '</em></p>';
        }

        // Create the post
        $post_id = wp_insert_post([
            'post_type' => 'post',
            'post_title' => sanitize_text_field($fields['recipe_name']),
            'post_content' => $content,
            'post_status' => 'publish',
            'post_category' => [$category_id],
        ]);

        // Check for errors
        if (is_wp_error($post_id)) {
            return [
                'type' => 'error',
                'message' => 'Failed to publish recipe: ' . $post_id->get_error_message()
            ];
        }

        return [
            'type' => 'success',
            'message' => 'Thank you! Your recipe has been published.'
        ];
    }
}
```

**Registration file (register-recipe-action.php):**

```php
<?php
/**
 * Register the Recipe Submission action
 */

add_action('init', function() {
    // Ensure Breakdance is installed and activated
    if (!function_exists('\Breakdance\Forms\Actions\registerAction') ||
        !class_exists('\Breakdance\Forms\Actions\Action')) {
        return;
    }

    // Include the action class file
    require_once __DIR__ . '/includes/RecipeSubmissionAction.php';

    // Register the action
    \Breakdance\Forms\Actions\registerAction(
        new \MyPlugin\Forms\Actions\RecipeSubmissionAction()
    );
});
```
