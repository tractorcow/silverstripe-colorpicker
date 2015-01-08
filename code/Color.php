<?php
/**
 * Color field-type
 * @author bummzack
 */
class Color extends Varchar
{
	private static $casting = array(
		'Luminance' => 'Float',
		'AlteredColorHSV' => 'Color'
	);

	/**
	 * Helper function to convert RGB to HSV
	 * @param int $R red channel, 0-255
	 * @param int $G green channel, 0-255
	 * @param int $B blue channel, 0-255
	 * @return array containing 3 values, H,S,V 0-1
	*/
	public static function RGB_TO_HSV ($R, $G, $B)  // RGB Values:Number 0-255
	{
		$HSV = array();

		$var_R = ($R / 255);
		$var_G = ($G / 255);
		$var_B = ($B / 255);

		$var_Min = min($var_R, $var_G, $var_B);
		$var_Max = max($var_R, $var_G, $var_B);
		$del_Max = $var_Max - $var_Min;

		$V = $var_Max;

		if ($del_Max == 0)
		{
			$H = 0;
			$S = 0;
		}
		else
		{
			$S = $del_Max / $var_Max;

			$del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
			$del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
			$del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

			if      ($var_R == $var_Max) $H = $del_B - $del_G;
			else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

			if ($H<0) $H++;
			if ($H>1) $H--;
		}

		$HSV[] = $H;
		$HSV[] = $S;
		$HSV[] = $V;

		return $HSV;
	}

	/**
	 * Helper function to convert HSV to RGB
	 * @param float $H hue 0-1
	 * @param float $S saturation 0-1
	 * @param float $V brightness 0-1
	 * @return array containing 3 values in the range from 0-255, R,G,B
	 */
	public static function HSV_TO_RGB($H, $S, $V) {
		$H *= 6;
		$I = floor($H);
		$F = $H - $I;
		$M = $V * (1 - $S);
		$N = $V * (1 - $S * $F);
		$K = $V * (1 - $S * (1 - $F));
		switch ($I) {
			case 0:
				list($R,$G,$B) = array($V,$K,$M);
				break;
			case 1:
				list($R,$G,$B) = array($N,$V,$M);
				break;
			case 2:
				list($R,$G,$B) = array($M,$V,$K);
				break;
			case 3:
				list($R,$G,$B) = array($M,$N,$V);
				break;
			case 4:
				list($R,$G,$B) = array($K,$M,$V);
				break;
			case 5:
			case 6: //for when $H=1 is given
				list($R,$G,$B) = array($V,$M,$N);
				break;
		}
		return array($R * 255.0, $G * 255.0, $B * 255.0);
	}

	/**
	 * Convert a hex string to separate R,G,B values
	 * @param string $hex
	 * @return array containing 3 integers (0-255) R,G,B
	 */
	public static function HEX_TO_RGB($hex){
		$RGB = array();

		$color = intval(ltrim($hex, '#'), 16);
		$r = ($color >> 16) & 0xff;
		$g = ($color >> 8) & 0xff;
		$b = $color & 0xff;

		$RGB[] = $r;
		$RGB[] = $g;
		$RGB[] = $b;

		return $RGB;
	}

	/**
	 * Convert R,G,B to hex
	 * @param int $R
	 * @param int $G
	 * @param int $B
	 * @return string
	 */
	public static function RGB_TO_HEX($R, $G, $B){
		return sprintf("%06X", ($R << 16) | ($G << 8) | $B);
	}

	/**
	 * Calculate luminance (Photometric/digital ITU-R)
	 * @param int $R
	 * @param int $G
	 * @param int $B
	 * @return number 0-1
	 */
	public static function RGB_TO_LUMINANCE($R, $G, $B){
		return min(1, max(0, 0.2126 * ($R / 255) + 0.7152 * ($G / 255) + 0.0722 * ($B / 255)));
	}

	public function __construct($name = null, $options = array()) {
		parent::__construct($name, 6, $options);
	}

	public function scaffoldFormField($title = null, $params = null) {
		$field = new ColorField($this->name, $title);
		return $field;
	}

	/**
	 * Get the red component of this color
	 * @return int red-component 0-255
	 */
	public function Red(){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		return $R;
	}

	/**
	 * Get the green component of this color
	 * @return int green-component 0-255
	 */
	public function Green(){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		return $G;
	}

	/**
	 * Get the blue component of this color
	 * @return int blue-component 0-255
	 */
	public function Blue(){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		return $B;
	}

	/**
	 * Get the color as CSS3 color definition with optional alpha value.
	 * Will return "rgba(RED, GREEN, BLUE, OPACITY)"
	 * @param number $opacity opacity value from 0-1
	 * @return string css3 color definition
	 */
	public function CSSColor($opacity = 1){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		$A = self::clamp($opacity, 0, 1);
		return sprintf('rgba(%d,%d,%d,%f)', $R, $G, $B, $A);
	}

	/**
	 * Return the luminance of the color
	 * @return the luminance 0-1
	 */
	public function Luminance(){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		return self::RGB_TO_LUMINANCE($R, $G, $B);
	}

	/**
	 * Change the color by the given HSV values and return a new color
	 * @param float $hChange hue change
	 * @param float $sChange saturation change
	 * @param float $vChange brightness change
	 * @return Color the new color
	 */
	public function AlteredColorHSV($hChange, $sChange, $vChange){
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		list($H, $S, $V) = self::RGB_TO_HSV($R, $G, $B);
		list($R, $G, $B) = self::HSV_TO_RGB(
			fmod($H + $hChange + 1, 1),
			self::clamp($S + $sChange, 0.0, 1.0),
			self::clamp($V + $vChange, 0.0, 1.0));
		$color = new Color();
		$color->setValue(self::RGB_TO_HEX($R, $G, $B));
		return $color;
	}

	/**
	 * Blend the color with a background color, with the given opacity level
	 * @param float $opacity Opacity level of the current color (between 0 - 1)
	 * @param string $background The background color
	 * @return string
	 */
	public function Blend($opacity, $background = 'FFFFFF') {
		list($R, $G, $B) = self::HEX_TO_RGB($this->value);
		list($bgR, $bgG, $bgB) = self::HEX_TO_RGB(ltrim($background, '#'));
		$opacity = self::clamp($opacity, 0, 1);
		
		$add = array(
			'r' => ($bgR - $R) / 100,
			'g' => ($bgG - $G) / 100,
			'b' => ($bgB - $B) / 100
		);
						
		$transparency = (1 - $opacity) * 100;
		$R += intval($add['r'] * $transparency);
		$G += intval($add['g'] * $transparency);
		$B += intval($add['b'] * $transparency);
		
		return self::RGB_TO_HEX($R, $G, $B);
	}

	private static function clamp($val, $min, $max){
		return min($max, max($min, $val));
	}
}
