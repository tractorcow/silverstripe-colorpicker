<?php
/**
 * Color field
 */
class ColorField extends TextField {
	
	public function __construct($name, $title = null, $value = '', $form = null){
		parent::__construct($name, $title, $value, 6, $form);
        
        $this->addExtraClass('text');
	}
	
	function Field($properties = array()) {
		$this->addExtraClass('ColorPickerInput');
		Requirements::javascript("colorpicker/javascript/colorpicker.js");
		Requirements::javascript("colorpicker/javascript/colorfield.js");
		Requirements::css("colorpicker/css/colorpicker.css");
		
		$style = 'background-image: none;background-color:' . ($this->value ? '#' . $this->value : '#ffffff'). 
				 '; color: ' . ($this->getTextColor()) . ';';
		$attributes = array(
			'type' => 'text',
			'class' => 'text text' . ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->getName(),
			'value' => $this->Value(),
			'tabindex' => $this->getAttribute('tabindex'),
			'maxlength' => ($this->maxLength) ? $this->maxLength : null,
			'size' => ($this->maxLength) ? min( $this->maxLength, 30 ) : null,
			'style' => $style
		);
		
		if($this->disabled) $attributes['disabled'] = 'disabled';
		return $this->createTag('input', $attributes);
	}

	function validate($validator)
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
	
	protected function getTextColor()
	{
		if($this->value) {
			$c = intval($this->value, 16);
			$r = $c >> 16;
			$g = ($c >> 8) & 0xff;
			$b = $c & 0xff;
			$mid = ($r + $g + $b) / 3;
			return ($mid > 127) ? '#000000' : '#ffffff';
		} else {
			return '#000000';
		}
	}
}

/**
 * Disabled version of {@link ColorField}.
 */
class ColorField_Disabled extends ColorField {
	
	protected $disabled = true;
	
	function Field($properties = array()) {
		if($this->value) {
			$val = '#' . $this->value;
		} else {
			$val = '#ffffff';
		}
		
		$col = $this->getTextColor();
		
		return "<span class=\"readonly\" id=\"" . $this->id() . "\" style=\"color:$col; background:$val;\">$val</span>
				<input type=\"hidden\" value=\"{$this->value}\" name=\"$this->name\" />";
	}
	
	function Type() { 
		return "date_disabled readonly";
	}
	
	function jsValidation() {
		return null;
	}

	function php() {
		return true;
	}
	
	function validate($validator) {
		return true;	
	}
}
?>