# Reusable Dependencies

Register reusable dependencies that can be used in any element on Element Studio.

## How to register a new reusable dependencies

```php
add_action('breakdance_reusable_dependencies_urls', function ($urls) {
   $urls['bootstrap'] = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js';
   return $urls;
});
```

## How to use a reusable dependencies

1. Open Element Studio
2. Navigate to the "Dependencies" tabs
3. Add a new dependency
4. In the Script URL field, write %%BREAKDANCE_REUSABLE_BOOTSTRAP%% (replace bootstrap with the name of your dependency)

We have some predefined reusable dependencies that you can use:

- %%BREAKDANCE_REUSABLE_GSAP%%
- %%BREAKDANCE_REUSABLE_SCROLL_TRIGGER%%

## How to change GSAP version

If you want to change the version of GSAP loaded globally by Breakdance, you can do so by using the following code:

```php
add_action('breakdance_reusable_dependencies_urls', function ($urls) {
   $urls['gsap'] = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js';
   return $urls;
});
```

By doing so, you will also need to update the ScrollTrigger version to match the GSAP version.

## Notes

1. You don't need to register an reusable dependency if you don't want to reuse it in other elements in Element Studio. You can just use the URL directly in your element.
2. Variables that are defined in camelCase must be used as snake_case on Element Studio.
