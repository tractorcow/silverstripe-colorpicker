<?php

namespace TractorCow\Colorpicker\Forms;

use TractorCow\Colorpicker\Color;
use SilverStripe\Forms\Form;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\TextField;

/**
 * Color field
 */
class ColorField extends TextField
{
    /**
     * Returns a color field.
     *
     * @param string $name
     * @param null|string $title
     * @param string $value
     * @param null|Form $form
     */
    public function __construct($name, $title = null, $value = '', $form = null)
    {
        parent::__construct($name, $title, $value, 16, $form);
    }

    public function Field($properties = [])
    {
        Requirements::javascript('tractorcow/silverstripe-colorpicker: client/javascript/colorfield.js');
        Requirements::javascript('tractorcow/silverstripe-colorpicker: client/javascript/colorpicker.js');
        Requirements::css('tractorcow/silverstripe-colorpicker: client/css/colorpicker.css');

        $this->addExtraClass('colorfield');

        $this->setAttribute('style', sprintf(
            'background-image: none; background-color: %s; %s',
            ($this->value ? '#' . $this->value : '#ffffff'), $this->getTextColorStyle()
        ));

        return parent::Field($properties);
    }

    /**
     * Override the type to get the proper class name on the field
     * "text" is needed here to render the form field as a normal text-field
     * @see FormField::Type()
     */
    public function Type()
    {
        return 'text';
    }

    /**
     * Ensure the color is a valid hexadecimal color
     * @see FormField::validate()
     *
     * @param \SilverStripe\Forms\Validator $validator
     * @return bool whether or not the field is valid
     */
    public function validate($validator)
    {
        if (!empty($this->value) && !preg_match('/^[A-f0-9]{6,8}$/', $this->value)) {
            $validator->validationError(
                $this->name,
                _t('ColorField.VALIDCOLORFORMAT', 'Please enter a valid color in hexadecimal format.'),
                'validation',
                false
            );
            return false;
        }
        return true;
    }

    /**
     * Calculate the text color style so that it's visible on the background
     * @return string
     */
    protected function getTextColorStyle()
    {
        // change alpha component depending on disabled state
        $a = $this->isDisabled() ? '0.5' : '1.0';
        if ($this->value) {
            list($R, $G, $B) = Color::HEX_TO_RGB($this->value);
            $luminance = Color::RGB_TO_LUMINANCE($R, $G, $B);
            // return color as hex and as rgba values (hex is fallback for IE-8)
            return ($luminance > 0.5) ?
                'color: #000; color: rgba(0, 0, 0, '. $a .');' :
                'color: #fff; color: rgba(255, 255, 255, '. $a .');';
        } else {
            return 'color: #000; color: rgba(0, 0, 0, '. $a .');';
        }
    }
}
