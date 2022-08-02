# Dynamic Data Field API

## Overview

Creating a Breakdance Dynamic Field involves two steps

1. Create a field class to represent your field
2. Register the field class with Breakdance

An example WordPress plugin that adds Dynamic Data fields can be found at https://github.com/soflyy/breakdance-sample-dynamic-data

# Creating a Field Class

To get started create a new PHP class. Your class will need to extend one of the Breakdance base classes in order for Breakdance to know how to work with your data, and which Breakdance Elements it will be compatible with.

## Base Field Classes

### StringField

`\Breakdance\DynamicData\StringField`

This is for any generic string data

**Handler Return Type**

`\Breakdance\DynamicData\StringData`

The StringData object has a `$value` property that must be assigned to a string.

**String Data Helpers**

- `StringData::fromString(string $string);` will return an instance of StringData with the value property set to $string
- `StringData::emptyString();` Will return an instance of StringData with the value set to an empty string

**Example Class**

```php
use Breakdance\DynamicData\StringField;
use Breakdance\DynamicData\StringData;

class MyDynamicField extends StringField
{
    /**
     * @return string
     */
    public function label()
    {
        return 'Dynamic String';
    }

    /**
     * @return string
     */
    public function category()
    {
        return 'My Plugin';
    }

    /**
     * @return string
     */
    public function slug()
    {
        return 'my_plugin_string';
    }

    /**
     * array $attributes
     */
    public function handler($attributes): StringData
    {
        return StringData::fromString('some string value');
    }
}
```

### ImageField

`\Breakdance\DynamicData\ImageField`

This is for fields that return an image

**Handler function return type**

`\Breakdance\DynamicData\ImageData`

**ImageData Properties**

The properties that are *required* for dynamic images to work with Breakdance Image elements are the $url and $sizes properties.

`$url` is the URL to the source image

`$sizes` is an array with keys for each size slug, and each value is an array containing 'file', 'width', 'height', and 'mime-type'.

It is recommended to use WordPress attachments and the fromAttachmentId helper for Dynamic Images

**Image Data Helpers**

- `ImageData::fromAttachmentId($attachmentId)` accepts an attachmentId as a string or integer and returns an instance of ImageData populated with all the required data from the attachment.

**Example Class**

```php
use Breakdance\DynamicData\ImageField;
use Breakdance\DynamicData\ImageData;

class MyDynamicField extends ImageField
{
    /**
     * @return string
     */
    public function label() {
        return 'Dynamic Image';
    }

    /**
     * @return string
     */
    public function category() {
        return 'My Plugin';
    }

    /**
    * @return string
    */
    public function slug()
    {
        return 'my_plugin_image';
    }

    /**
     * @param array $attributes
     */
    public function handler($attributes): ImageData {

        // build from attachment data
        $attachmentData = wp_prepare_attachment_for_js($attachmentId);
        $imageData = new ImageData;
        $imageData->id = (string) $attachmentData['id'];
        $imageData->filename = $attachmentData['filename'];
        $imageData->alt = $attachmentData['alt'];
        $imageData->caption = $attachmentData['caption'];
        $imageData->url = $attachmentData['url'];
        $imageData->type = $attachmentData['type'];
        $imageData->mime = $attachmentData['mime'];
        $imageData->sizes = $attachmentData['sizes'];

        // or using the helper
        $imageData = ImageData::fromAttachmentId($attachmentId);
        
        return $imageData;
    }
}
```

### GalleryField

The Gallery field is for fields that return multiple images to be used with Slideshow or Gallery elements.

**Handler Function Return Type**

`\Breakdance\DynamicData\GalleryData`

GalleryData has a property `$images` that must be set to an array of `\Breakdance\DynamicData\ImageData` objects

**Example Class**

```php
use Breakdance\DynamicData\GalleryField;
use Breakdance\DynamicData\GalleryData;

class MyDynamicField extends GalleryField
{
    /**
     * @return string
     */
    public function label() {
        return 'Dynamic Gallery';
    }

    /**
     * @return string
     */
    public function category() {
        return 'My Plugin';
    }

    /**
    * @return string
    */
    public function slug()
    {
        return 'my_plugin_gallery';
    }

    /**
     * @param array $attributes
     */
    public function handler($attributes): GalleryData {
        // fetch IDs for images attached to the current post
    $attachedImages = get_attached_media('image', get_the_ID());

        $gallery = new GalleryData();
        // map WordPress attachments to ImageData
        $gallery->images = array_map(static function($attachment) {
            return ImageData::fromAttachmentId($attachment->ID);
        }, $attachedImages);

        return $gallery;
    }
}
```

### OembedField

The Oembed field is for fields that return a video, either by URL to an oembed provider (e.g YouTube) or directly to a video file from the media library.

**Handler Function Return Type**

`\Breakdance\DynamicData\OembedData`

**OembedData Properties**

The properties that are *required* for Videos to work with Breakdance video elements are the $embedUrl, $type, and, if the video is from an oembed source, $provider.

`$embedUrl` is the URL to the oembed or video source

`$type`  The video type, either `video` for direct video embed, or `oembed`

**Helpers**

`OembedData::fromOembedUrl($url)` - will attempt to retrieve the oembed data via the passed `$url` using an XHR request. This is useful for video providers [e.g](http://e.gh) YouTube or Vimeo

`OembedData::emptyOembed()` - this will return an OembedData object with empty properties.

**Example Class**

```php
use Breakdance\DynamicData\OembedField;
use Breakdance\DynamicData\OembedData;

class MyDynamicField extends OembedField
{
    /**
     * @return string
     */
    public function label() {
        return 'Dynamic Video';
    }

    /**
     * @return string
     */
    public function category() {
        return 'My Plugin';
    }

    /** 
    * @return string
    */
    public function slug()
    {
        return 'my_plugin_video';
    }

    /**
     * @param array $attributes
     */
    public function handler($attributes): OembedData {
        // retrieve the video from your plugin or application
        $videoData = get_the_video_data();

        // create a new OembedData object
        $oembedData = new OembedData;
        $oembedData->title = $videoData['title'];
        $oembedData->provider = 'video';
        $oembedData->embedUrl = $videoData['url'];
        $oembedData->thumbnail = $videoData['thumbnail'];
        $oembedData->format = 'video/mp4';
        $oembedData->type = 'video';

        // or using the helper 
        $oembedData = OembedData::fromOembedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        
        return $oembedData;
    }
}
```

## Required Methods

There are four mandatory methods that must be implemented in your field class

label  - takes no argument and returns a string that will be used to identify your fields in the Dynamic Data Field selection window

```php
/**
 * @return string
 */
public function label() {
    return 'My Dynamic Field';
}
```

category - takes no arguments and returns a string for the category to group the fields in

```php
/**
 * @return string
 */
public function category() {
    return 'My Plugin';
}
```

slug - takes no arguments and returns a string to identify the field  handler for output. This should be unique across all dynamic data fields, so it is recommended to prefix the slug appropriately.

```php
/**
 * @return string
*/
public function slug()
{
    return 'my_plugin_field';
}
```

handler - accepts an array of attributes and returns a data object (see below) that matches the parent field type

```php
/**
 * @param array $attributes
*/
public function handler($attributes): StringData {
    return StringData::fromString('My Dynamic Field Output');
}

```

## Optional Methods

**Subcategory**

If you wish to further group your dynamic fields within the dialog, you can optionally include a sub category.

```php
/**
 *@return string
 */
public function subcategory()
{
    return 'My dynamic field subcategory';
}
```

**Return Types**

The return types determine which elements will display in the dialog for the selected element. A default return type is configured for each base class type. More on the available return types below.

```php
/**
 *@return string
 */
public function returnTypes()
{
    return ['string'];
}
```

## Return Types

The return types determine which elements will have access to a dynamic field. For example, an Image Element will only be able to accept data from fields that return the Image return type. The returnTypes method returns an array, so a field may have multiple return types. For example, String and URL.

`string` - Generic string, default return type for StringData

`image_url` - Array of image properties, default return type for ImageData

`gallery` - Array of ImageData, default return type for GalleryData

`video` - Array of Video or Oembed data. Default return type for Oembed Fields

`url` - String that represents a URL. Compatible with StringData

`query` - String that represents a URL query. Compatible with StringData

`google_map` - String that represents an address or Latitude/Longitude location. Compatible with StringData and works with the Google Map Element

## Registering The Field Class With Breakdance

Once your class is created, registering the field is simple, just call the following registerField helper and pass in a new instance of your field class.The field will now be available as an option in the Dynamic Data modal dialog.

**Note:** To prevent file loading race conditions, it is recommended to call the register helper from inside a WordPress action, e.g init.

```php
add_action('init', function() {
    // Check if Breakdance is installed and class/function exists
    if (!function_exists('\Breakdance\DynamicData\registerField') || !class_exists('\Breakdance\DynamicData\Field')) {
        return;
    }
    
    \Breakdance\DynamicData\registerField(new MyField());
}
```

## Multiple Fields With The Same Handler

In some cases you may wish to register multiple fields that are of the same data type and have the same handler function. Instead of creating a class for each field, you can create a single class and register multiple instances of the same class.

```php
// MyDynamicField.php

use Breakdance\DynamicData\StringField;
use Breakdance\DynamicData\StringData;

class MyDynamicField extends \Breakdance\DynamicData\StringField
{

    protected array $fieldData;

    /**
     *@return string
     */
    public function __construct($fieldData)
  {
      $this->fieldData = $fieldData;
  }

    /**
     * @return string
     */
    public function label()
    {
        return $this->fieldData['label'];
    }

    /**
     * @return string
     */
    public function category()
    {
        return 'My Plugin';
    }
    
    /**
     * @return string
     */
    public function subcategory()
    {
        return $this->fieldData['group'];
    }

    /**
     * @return string
     */
    public function slug()
    {
        return 'my_plugin_field_' . $this->fieldData['slug'];
    }
    /**
     * @return string
     */
    public function handler($attributes): StringData
    {
        $value = (string) get_the_field_value($this->fieldData['slug']);
        return StringData::fromString($value);
    }
}

// register-fields.php included by your plugin

add_action('init', function() {
    // fail if Breakdance is not installed and available
    if (!function_exists('\Breakdance\DynamicData\registerField') || !class_exists('\Breakdance\DynamicData\Field')) {
        return;
    }
    
    require_once('my-dynamic-field.php');
    
    $myFields = [[
        'label' => 'My Field One',
      'slug' => 'my_field_slug_one',
      'group' => 'My Plugin',
    ],[
        'label' => 'My Field Two',
      'slug' => 'my_field_slug_two',
      'group' => 'My Plugin',
    ]];
    
    // loop through fields array and register fields
    foreach ($myFields as $fieldData) {
        \Breakdance\DynamicData\registerField(new MyField($fieldData));
    }
});
```
