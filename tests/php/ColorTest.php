<?php

namespace TractorCow\Colorpicker\Tests;

use SilverStripe\Dev\SapphireTest;
use TractorCow\Colorpicker\Color;

class ColorTest extends SapphireTest
{
    /**
     * Test conversion from RGB to HSV
     */
    public function testRGBtoHSV()
    {
        // Red
        $hsv = Color::RGB_TO_HSV(255, 0, 0);
        $this->assertEquals([0, 1, 1], $hsv);

        // Yellow
        $hsv = Color::RGB_TO_HSV(255, 255, 0);
        $this->assertEquals([1 / 360 * 60, 1, 1], $hsv);

        // Teal
        $hsv = Color::RGB_TO_HSV(63.75, 127.5, 127.5);
        $this->assertEquals([.5, .5, .5], $hsv);

        // Black
        $hsv = Color::RGB_TO_HSV(0, 0, 0);
        $this->assertEquals([0, 0, 0], $hsv);

        // Invalid? Should be equal to white
        $hsv = Color::RGB_TO_HSV(400, 330, 890);
        $this->assertEquals([0, 0, 1], $hsv);
    }

    /**
     * Test conversion from HSV to RGB
     */
    public function testHSVtoRGB()
    {
        // Red
        $rgb = Color::HSV_TO_RGB(0, 1, 1);
        $this->assertEquals([255, 0, 0], $rgb);

        // Yellow
        $rgb = Color::HSV_TO_RGB(1 / 360 * 60, 1, 1);
        $this->assertEquals([255, 255, 0], $rgb);

        // Teal
        $rgb = Color::HSV_TO_RGB(.5, .5, .5);
        $this->assertEquals([63.75, 127.5, 127.5], $rgb);

        // Black
        $rgb = Color::HSV_TO_RGB(0, 0, 0);
        $this->assertEquals([0, 0, 0], $rgb);

        // Invalid values. Make sure these are properly clamped. Result should be red
        $rgb = Color::HSV_TO_RGB(-10, 4, 2);
        $this->assertEquals([255, 0, 0], $rgb);
    }

    /**
     * Test HEX to RGB
     */
    public function testHEXtoRGB()
    {
        $rgb = Color::HEX_TO_RGB('#ff0000');
        $this->assertEquals([255, 0, 0], $rgb);

        $rgb = Color::HEX_TO_RGB('#ff8000');
        $this->assertEquals([255, 128, 0], $rgb);

        $rgb = Color::HEX_TO_RGB('#408080');
        $this->assertEquals([64, 128, 128], $rgb);
    }

    /**
     * Test RGB to HEX
     */
    public function testRGBtoHEX()
    {
        $hex = Color::RGB_TO_HEX(255, 0, 0);
        $this->assertEquals('FF0000', $hex);

        $hex = Color::RGB_TO_HEX(255, 128, 0);
        $this->assertEquals('FF8000', $hex);

        $hex = Color::RGB_TO_HEX(64, 128, 128);
        $this->assertEquals('408080', $hex);

        // invalid color values should be clamped
        $hex = Color::RGB_TO_HEX(800, -30, 256);
        $this->assertEquals('FF00FF', $hex);
    }

    /**
     * Test RGB to Luminance
     */
    public function testRGBtoLuminance()
    {
        $l = Color::RGB_TO_LUMINANCE(255, 0, 0);
        $this->assertEquals(.2126, $l);

        $l = Color::RGB_TO_LUMINANCE(0, 255, 0);
        $this->assertEquals(.7152, $l);

        $l = Color::RGB_TO_LUMINANCE(0, 0, 255);
        $this->assertEquals(.0722, $l);

        $l = Color::RGB_TO_LUMINANCE(255, 255, 255);
        $this->assertEquals(1, $l);
    }

    /**
     * Test different color methods
     */
    public function testColorMethods()
    {
        $color = new Color();
        $color->setValue('4080ff');

        // Color-Components
        $this->assertEquals(64, $color->Red());
        $this->assertEquals(128, $color->Green());
        $this->assertEquals(255, $color->Blue());

        // Luminance
        $this->assertEquals(64 / 255 * .2126 + 128 / 255 * .7152 + .0722, $color->Luminance());

        // CSS
        $this->assertEquals('rgba(64,128,255,1.000000)', $color->CSSColor());
        $this->assertEquals('rgba(64,128,255,0.500000)', $color->CSSColor(.5));

        $this->assertEquals('4080ff', $color->forTemplate());
    }

    /**
     * Test color blending method
     */
    public function testBlend()
    {
        $color = new Color();
        $color->setValue('ff8040');

        $mix = $color->Blend(.75);
        // make sure we get a color
        $this->assertInstanceOf(Color::class, $mix);
        $this->assertEquals('FF9F6F', $mix->forTemplate());

        $this->assertEquals('FFDFCF', $color->Blend(.25)->forTemplate());
        $this->assertEquals('402010', $color->Blend(.25, '#000000')->forTemplate());
        $this->assertEquals('80809F', $color->Blend(.50, '0080ff')->forTemplate());

        // Blending with 1 or 0 opacity
        $this->assertEquals('FFFFFF', $color->Blend(0)->forTemplate());
        $this->assertEquals('FF8040', $color->Blend(1)->forTemplate());

        // Chain methods
        $this->assertEquals('C0CF78', $color->Blend(.50)->Blend(.75, '#00ff00')->forTemplate());
    }

    /**
     * Test color HSV shifting method
     */
    public function testHsvAlteration()
    {
        $color = new Color();
        $color->setValue('80FF40');

        $altered = $color->AlteredColorHSV(.5, 0, 0);
        // make sure we get a color
        $this->assertInstanceOf(Color::class, $altered);
        $this->assertEquals('BE40FF', $altered->forTemplate());
        $this->assertEquals('A900FF', $color->AlteredColorHSV(.5, .5, 0)->forTemplate());
        $this->assertEquals('557F3F', $color->AlteredColorHSV(0, -.25, -.5)->forTemplate());
        // No change doesn't change the color value
        $this->assertEquals('80FF40', $color->AlteredColorHSV(0, 0, 0)->forTemplate());
    }
}
