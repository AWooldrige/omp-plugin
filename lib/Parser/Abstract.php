<?php

abstract class Parser_Abstract {
    private $raw = null;
    private $consumable = null;
    private $cooked = null;

    abstract public function parse();

    /**
     * Splits the recipe into paragraphs. Not bothered how many linebreaks
     * inbetween paragraphs either. Expects all linebreaks to be represented
     * by the PHP_EOL delimeter.
     *
     * @param string $raw Original recipe to split into paragraphs
     * @return array array of paragraphs
     */
    public function splitParagraphs($raw) {
        return preg_split('/'.PHP_EOL."(\s|".PHP_EOL.")*".PHP_EOL.'/', $raw);
    }

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
