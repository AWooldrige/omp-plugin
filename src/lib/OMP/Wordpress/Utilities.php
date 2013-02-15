<?php

/**
 * OMP_Wordpress_Utilities
 *
 * Utilities that are related to WordPress rather than the recipe parser, to
 * keep some separation
 *
 * @package OMP
 * @subpackage Wordpress
 * @copyright 1997-2012 Alistair Wooldrige
 * @author Alistair Wooldrige <alistair@wooldrige.co.uk>
 * @license Apache License, Version 2.0 {@link http://www.apache.org/licenses/LICENSE-2.0}
 */
class OMP_Wordpress_Utilities {

    /**
     * Return an array always containing one element, the newest item added to
     * the sticky posts array.
     *
     * This is used to only allow one sticy post on a WordPress instance at a
     * time. This will be used in a hook when updating the sticky_posts option.
     * As we are supplied both the new and the old array, we can tell which
     * post is the one about to be stickied, and remove all others from the
     * list.
     *
     * @param array $old old array of sticky post ids, currenlty in the db
     * @param array $new new array of sticky post ids
     * @static
     * @access public
     * @return array containing one element, the post id of the most recent sticky
     */
    public static function oneStickyPost($old, $new) {
        if(count($new) <= 1) {
            return $new;
        }

        $diff = array_diff($new, $old);
        return array(array_pop($diff));
    }

    /**
     * Convert a human like duration sentance into ISO8601 duration format.
     * E.g. '6 days, 8 hours' would convert to 'P6DT8H'
     *
     * @param string $durationString human duration sentance
     * @static
     * @access public
     * @return string ISO8601 format duration string
     */
    public static function convertHumanDurationToIso8601($durationString) {
        $keyMap = array(
            's' => 'S',
            'i' => 'M',
            'h' => 'H',
            'd' => 'D',
            'm' => 'M',
            'y' => 'Y'
        );
        $duration = DateInterval::createFromDateString($durationString);
        $timeComponent = '';
        $dateComponent = '';
        foreach(array('h', 'i', 's') as $key) {
            if($duration->$key > 0) {
                $timeComponent .= $duration->$key . $keyMap[$key];
            }
        }
        foreach(array('y', 'm', 'd') as $key) {
            if($duration->$key > 0) {
                $dateComponent .= $duration->$key . $keyMap[$key];
            }
        }
        $iso = 'P' . $dateComponent;
        if(mb_strlen($timeComponent) > 0) {
            $iso .= 'T' . $timeComponent;
        }
        return $iso;
    }
}
