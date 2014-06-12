<?php
/**
 * Color field
 */
class ColorField extends TextField {
	
	public function __construct($name, $title = null, $value = '', $form = null){
		parent::__construct($name, $title, $value, 6, $form);
	}
	
	public function Field($properties = array()) {
		$this->addExtraClass('colorfield');
		Requirements::javascript(COLORPICKER_DIR . '/javascript/colorpicker.js');
		Requirements::javascript(COLORPICKER_DIR . '/javascript/colorfield.js');
		Requirements::css(COLORPICKER_DIR . '/css/colorpicker.css');
		
		$this->setAttribute('style', 
			'background-image: none; '.
			'background-color:'. ($this->value ? '#' . $this->value : '#ffffff').
			'; ' . $this->getTextColorStyle());
		
		return parent::Field($properties);
	}
	
	/**
	 * Override the type to get the proper class name on the field
	 * "text" is needed here to render the form field as a normal text-field
	 * @see FormField::Type()
	 */
	public function Type(){
		return 'text';
	}

	/**
	 * Ensure the color is a valid hexadecimal color
	 * @see FormField::validate()
	 */
	public function validate($validator)
	{
		if(!empty ($this->value) && !preg_match('/^[A-f0-9]{6}$/', $this->value))
		{
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
		if($this->value) {
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
