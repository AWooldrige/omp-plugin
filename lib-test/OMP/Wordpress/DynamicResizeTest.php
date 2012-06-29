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
     * Computing height to maintain aspect ratio with fixed width
     */
    public function test_isSizeMatchWithWidthBounded() {

        //Large height, exact width
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(100, null, 100, 400)
        );

        //Small height, exact width
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(100, null, 100, 50)
        );

        //Too small width
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(100, null, 99, 400)
        );

        //Too large width
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(100, null, 101, 400)
        );
    }

    /**
     * Computing width to maintain aspect ratio with fixed height
     */
    public function test_isSizeMatchWithHeightBounded() {

        //Large width, exact height
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(null, 100, 400, 100)
        );

        //Small width, exact height
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(null, 100, 50, 100)
        );

        //Too small heigh
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(null, 100, 400, 99)
        );

        //Too large height
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(null, 100, 400, 101)
        );
    }

    /**
     * Exact matches
     */
    public function test_isSizeMatchWithExactMatch() {

        //Exact image size matches should return true
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(400, 100, 400, 100)
        );
        $this->assertTrue(
            OMP_Wordpress_DynamicResize::isSizeMatch(1, 1234, 1, 1234)
        );


        //Both width and height don't match
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(400, 100, 1, 2)
        );
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(1, 1234, 4, 5)
        );

        //Only width matches
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(400, 100, 400, 50)
        );

        //Only height matches
        $this->assertFalse(
            OMP_Wordpress_DynamicResize::isSizeMatch(400, 100, 50, 100)
        );
    }
}
