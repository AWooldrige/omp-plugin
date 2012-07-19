<?php

class OMP_Wordpress_DynamicResizeTest extends PHPUnit_Framework_TestCase {

    /**
     * Wouldn't ever provided null null, if we weren't interested in resized
     * images, then we shouldn't by going through a resize function
     */
    public function test_isSizeMatchWithBothNull() {
        $this->setexpectedexception('InvalidArgumentException');
        OMP_Wordpress_DynamicResize::isSizeMatch(null, null, 400, 100);
    }


    /**
     * test_isSizeMatch
     * @dataProvider dataProvider_isSizeMatch
     */
    public function test_isSizeMatch($eWidth, $eHeight, $width, $height, $result) {

        $result = OMP_Wordpress_DynamicResize::isSizeMatch(
            $eWidth,
            $eHeight,
            $width,
            $height
        );

        if (true === $result) {
            $this->assertTrue(
                $result,
                'isSizeMatch returned incorrect boolean, expecting true'
            );
        } else {
            $this->assertFalse(
                $result,
                'isSizeMatch returned incorrect boolean, expecting false'
            );
        }
    }
    /**
     * Returns data array for isSizeMatch testing. Parameters are:
     * 1 - (int|null) width in pixels (or null to maintain aspect ratio) of the
     *     expected image
     * 2 - (int|null) height in pixels (or null to maitain aspect ratio) of the
     *     expected image
     * 3 - int width in pixels of the actual image
     * 4 - int height in pixels of the actual image
     *
     * 5 - boolean expected result of the function
     */
    public function dataProvider_isSizeMatch() {
        return array(

            /**
             * Computing height to maintain aspect ratio with fixed width
             */

            //Large height, exact width
            array(100, null, 100, 400, true),

            //Small height, exact width
            array(100, null, 100, 50, true),

            //Too small width
            array(100, null, 99, 400, false),

            //Too large width
            array(100, null, 101, 400, false),


            /**
             * Computing width to maintain aspect ratio with fixed height
             */

            //Large width, exact height
            array(null, 100, 400, 100, true),

            //Small width, exact height
            array(null, 100, 50, 100, true),

            //Too small heigh
            array(null, 100, 400, 99, false),

            //Too large height
            array(null, 100, 400, 101, false),


            /**
             * Exact matches
             */

            //Exact image size matches should return true
            array(400, 100, 400, 100, true),
            array(1, 1234, 1, 1234, true),

            //Both width and height don't match
            array(400, 100, 1, 2, false),
            array(1, 1234, 4, 5, false),

            //Only width matches
            array(400, 100, 400, 50, false),

            //Only height matches
            array(400, 100, 50, 100, false)
            );
    }




    /**
     * Wouldn't ever provide null null, if we weren't interested in resized
     * images, then we shouldn't by going through a resize function
     */
    public function test_calculateSizeWithBothNull() {
        $this->setexpectedexception('InvalidArgumentException');
        OMP_Wordpress_DynamicResize::calculateSize(null, null, 400, 100);
    }

    /**
     * test_calculateSize
     * @dataProvider dataProvider_calculateSize
     */
    public function test_calculateSize(
        $eWidth,
        $eHeight,
        $width,
        $height,
        $rWidth,
        $rHeight,
        $rCrop
    ) {

        $actual = OMP_Wordpress_DynamicResize::calculateSize(
            $eWidth,
            $eHeight,
            $width,
            $height
        );

        $expected = array(
            'width'  => $rWidth,
            'height' => $rHeight,
            'crop'   => $rCrop
        );

        $this->assertEquals(
            $expected,
            $actual,
            'calculateSize returned incorrect result'
        );
    }
    /**
     * Returns data array for calculateSize testing. Parameters are:
     * 1 - (int|null) width in pixels (or null to maintain aspect ratio) of the
     *     expected image
     * 2 - (int|null) height in pixels (or null to maitain aspect ratio) of the
     *     expected image
     * 3 - int width in pixels of the original image
     * 4 - int height in pixels of the original image
     *
     * 5 - int expected resultant width
     * 6 - int expected resultant height
     * 7 - boolean expected resultant crop or resize status (true to resize)
     */
    public function dataProvider_calculateSize() {
        return array(

            /**
             * If we are bounded by width, ensure that the height is calculated
             * correctly, and the resultant transformation is to resize
             */

            //Downscaling the height
            array(
                400, null,
                1024, 800,
                400, 312, true
            ),

            //Upscaling the height
            array(
                4000, null,
                1024, 800,
                4000, 3125, true
            ),

            //Equal height
            array(
                1024, null,
                1024, 800,
                1024, 800, true
            ),


            /**
             * If we are bounded by height, ensure that the width is calculated
             * correctly, and the resultant transformation is to resize
             */
            //Downscaling the width
            array(
                null, 400,
                800, 1024,
                312, 400, true
            ),

            //Upscaling the width
            array(
                null, 4000,
                800, 1024,
                3125, 4000, true
            ),

            //Equal width
            array(
                null, 1024,
                800, 1024,
                800, 1024, true
            ),


            /**
             * If the expected width and height are both provided, we should
             * crop as otherwise the aspect ratio will be squashed
             */
            //Exact width and height
            array(
                400, 100,
                400, 100,
                400, 100, false
            ),

            array(
                1, 1234,
                1, 1234,
                1, 1234, false
            ),

            //Both width and height don't match
            array(
                400, 100,
                10, 20,
                400, 100, false
            )
        );
    }
}
