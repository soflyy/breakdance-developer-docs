Breakdance Editor Save Actions
===
The following filters only run when clicking "Save" when editing a page/template inside the Breakdance editor. By default, these filters return true. You can use functions to have these filters return false based on your desired criteria. 

<span style="color:#EA552B">**These filters are experimental and they may be subject to change or removal**</span>


## Filters
- `breakdance_save_global_settings`- this filter is used to enable/disable saving Global Settings in Breakdance when the "Save" button is clicked in the Editor. 
- `breakdance_save_presets` - this filter is used to enable/disable saving Design Presets in Breakdance when the "Save" button is clicked in the Editor. 
- `breakdance_save_selectors` - this filter is used to enable/disable saving Custom Selectors and Classes in Breakdance when the "Save" button is clicked in the Editor. 
- `breakdance_save_ai_settings` - - this filter is used to enable/disable saving the Breakdance AI Settings when the "Save" button is clicked in the Editor 

## Example
The example below disables all four filters for all users except the user with the matching ID: 

```php
function disable_saving_for_other_users($allow_save) {
    $user_id = get_current_user_id(); // Get the current user's ID
    if ($user_id != 69420) {
        return false; // Disable saving if the user ID is not 69420
    }
    return $allow_save; // Allow saving otherwise
}

// Apply the filter to each saving function
add_filter('breakdance_save_global_settings', 'disable_saving_for_other_users');
add_filter('breakdance_save_presets', 'disable_saving_for_other_users');
add_filter('breakdance_save_selectors', 'disable_saving_for_other_users');
add_filter('breakdance_save_ai_settings', 'disable_saving_for_other_users');
```