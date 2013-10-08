# silverstripe-colorpicker

The ColorPicker Module adds a color-picker input field to the SilverStripe CMS. It makes use of the ColorPicker jQuery
Plugin.

## History

This module was taken from http://bummzack.ch/colorpicker/ and is the work of Roman Schmid, AKA banal. There is more
infomration regarding the history of this module at http://www.silverstripe.org/customising-the-cms/show/6114

Dimension27 have created a git repo for it so as it can easily be reused in silverstripe projects.

## Installation

 * Extract all files into the 'colorpicker' folder under your Silverstripe root, or install using composer

```bash
composer require "tractorcow/silverstripe-colorpicker" "3.0.*@dev"
```

## Usage

Adding a ColorField to your Page is as simple as this:

```php
// place this inside your getCMSFields method
$fields->addFieldToTab(
    'Root.Main', 
    new ColorField('BgColor', 'Background Color')
); 
```

