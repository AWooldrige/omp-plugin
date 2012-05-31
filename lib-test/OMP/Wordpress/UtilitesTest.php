<?php

class OMP_Wordpress_UtilitiesTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider dataProvider_oneStickyPost
     */
    public function test_oneStickyPost($old, $new, $expected) {
        $this->assertEquals(
            $expected,
            OMP_Wordpress_Utilities::oneStickyPost($old, $new)
        );
    }

    public function dataProvider_oneStickyPost() {
        return array(
            //Old array contains more than one post (e.g. first time using the
            //new filter)
            array(
                array(3,107,5),
                array(3,107,5,28),
                array(28)
            ),

            //Old array is empty (no current sticky posts)
            array(
                array(),
                array(3),
                array(3)
            ),

            //New array is empty (remove the only sticky post)
            array(
                array(38),
                array(),
                array()
            ),

            //Strange ordering
            array(
                array(38282,107,341),
                array(107,38282,341,9388),
                array(9388)
            ),

            //Just in case WordPress suddenly allows multi post stickying
            array(
                array(2),
                array(10,2,4,8),
                array(8)
            )
        );
    }
}
