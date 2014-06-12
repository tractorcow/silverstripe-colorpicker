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

