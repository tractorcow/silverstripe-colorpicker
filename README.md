# silverstripe-colorpicker

The ColorPicker Module adds a color-picker input field to the SilverStripe CMS. It makes use of the ColorPicker jQuery
Plugin.

## Installation

 * Extract all files into the 'colorpicker' folder under your Silverstripe root, or install using composer

```bash
composer require "tractorcow/silverstripe-colorpicker" "3.0.*@dev"
```

## Usage

Here's how you define a DB field to be a color:

```php
private static $db = array(
    'BgColor' => 'Color'
);
```
    
That's all... scaffolding will take care of creating the appropriate form-field.

If you use `getCMSFields` to create your fields yourself, you might want to do something like this:

```php
public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldToTab(
    	'Root.Main', 
    	new ColorField('BgColor', 'Background color')
    );

    return $fields;
}
```

### Tips for using the Color fieldtype in templates

The `Color` fieldtype provides some helper methods that can be useful in templating. Let's consider the above scenario where you have a Field named 'BgColor'. The most common use-case is something like this:

```html
<body style="background-color: #$BgColor;">
...
```

But there's more. You could also use CSS3 `rgba` color definitions with alpha. Example:

```html
<body style="background-color: #$BgColor; background-color: $BgColor.CSSColor(0.5);">
...
```

This will color your background with an alpha value of `0.5` (browsers that don't support rgba, such as IE-8 will fall back to the first background-color definition, that's why it's still in there).

Here's a complete list of the `Color` methods available in templates:

 - `Red` returns the red color component
 - `Green` returns the green color component
 - `Blue` returns the blue color component
 - `CSSColor` returns the color as `rgba`. The alpha value can be specified with the (optional) argument.
 - `Luminance` the luminance of the color as a floating-point value ranging from 0-1
 - `Blend` blends the color with a second background color (defaults to #FFFFFF) with the given opacity. `$BGColor.Blend(0.5, '#000000')` will give the color 50% opacity and put it on top of a black background.
 - `AlteredColorHSV` modifies the current color by the given HSV values. These values are offsets, so you could do something like this: `$BgColor.AlteredColorHSV(0.5, 0, 0)` which will return the color with the opposite hue. All parameters are percentage based and range from `0 - 1`. So doing: `$BgColor.AlteredColorHSV(0, 0, -0.2)` will result in a color with 20% less brightness (absolute, not relative).

 
