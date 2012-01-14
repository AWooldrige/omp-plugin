<?php

abstract class Parser_Abstract {
    private $raw = null;
    private $consumable = null;
    private $cooked = null;

    abstract public function parse();

    /**
     * Splits line breaks into seperate array entries. If there are no new
     * linebreaks in the string, an array with one element is still returned.

     * @param string $raw Original recipe to split new lines 
     * @return array array of each line of the $raw
     */
    public function splitNewlines($raw) {
        return split(PHP_EOL, $raw);
    }
}
