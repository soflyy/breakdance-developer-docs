
Breakdance AI Endpoints
===
Breakdance AI has several filters you can use to change the model or provider being used for Breakdance AI. 

When using these filters is that the information found at Breakdance > Settings > AI Assistant in WP-Admin will not change. You will want to check the developer's console and the usage charts for your provider to verify everything is working correctly. 

Additionally, you will add your API Key for your desired AI provider to the "OpenAI API Key" input area located at Breakdance > Settings > AI Assistant in WP-Admin. Breakdance will use the API key found here for whatever endpoint is set.

## Filters
- `breakdance_ai_model` - this filter allows you to define the model that Breakdance AI will use.
- `breakdance_ai_api_endpoint` - this filter lets you change the AI provider endpoint used for Breakdance AI. 
- `breakdance_ai_enabled` - this filter lets you choose whether or not Breakdance AI is viaible within Breakdance. 

## Examples
Here are some examples for how to change AI Models. 

### Example 1: Change OpenAI Model to gpt-4
To change the OpenAI model, use the `breakdance_ai_model` filter to set a new Model: 

```php
function override_breakdance_ai_model($model_version, $model) {
    return 'gpt-4';
}
add_filter('breakdance_ai_model', 'override_breakdance_ai_model', 10, 2);
```

### Example 2: Use OpenRouter with Claude
In this example, we're going to use OpenRouter instead of OpenAI, and we're going to use the Anthropic: Claude 3.5 Sonnet model. 

**Step 1: Add the Endpoint Filter**
The first thing we're going to do is set the URL filter. For OpenRouter, we need to use `https://openrouter.ai/api`:


```php
function override_breakdance_ai_endpoint($url) {
    $url = 'https://openrouter.ai/api';
    return $url;
}
add_filter('breakdance_ai_api_endpoint', 'override_breakdance_ai_endpoint');
```

**Step 2: Add the Model Filter**
Next, we're going to set the Model. We're going to use the Anthropic: Claude 3.5 Sonnet model in this case. Please note this model does require you to have credits with OpenRouter. There may be some other free models available that do not need credits. 

To know the string for the Model Version, we can visit the Model on OpenRouter and copy the string right below the title.

```php
function override_breakdance_ai_model($model_version, $model) {
    $model_version = 'anthropic/claude-3.5-sonnet';
    return $model_version;
}
add_filter('breakdance_ai_model', 'override_breakdance_ai_model', 10, 2);
```

**Step 3: Add our API Key**
Next, we need to create an API Key with OpenRouter. Once the key is created, we're going to add it to Breakdance > Settings > AI Assistant in WP-Admin and paste it into the "OpenAI API Key" input area.

### Example 3: Disable Breakdance AI
To disable Breakdance AI entirely within Breakdance, use the `breakdance_ai_enabled` hook: 

```php
add_filter('breakdance_ai_enabled', '__return_false');
```