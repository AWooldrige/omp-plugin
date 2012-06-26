<?php

class OMP_Wordpress_DynamicResizeTest extends PHPUnit_Framework_TestCase {
    public function test_placeholder() {
        $this->markTestIncomplete('Test the Kovshenin image code');
    }


    /**
     * Wouldn't ever provided null null, if we weren't interested in resized
     * images, then we shouldn't by going through a resize function
     */
    public function test_isSizeMatchWithBothNull() {
        //$this->setexpectedexception('InvalidArgumentException');
        //OMP_Wordpress_DynamicResize::isSizeMatch(null, null, 400, 100);
        $this->markTestIncomplete();
    }
    public function test_isSizeMatchWithWidthBounded() {
        //OMP_Wordpress_DynamicResize::isSizeMatch($width, null);
        $this->markTestIncomplete();
    }
}
