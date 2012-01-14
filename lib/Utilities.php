<?php
class Utilities {

    /**
     * Splits this supplied text into an array, with each element containing
     * one paragraph. Expects all linebreaks to be represented by the PHP_EOL
     * delimeter.
     *
     * @param string $raw string to split into paragraphs
     * @return array array of paragraphs
     */
    public function splitOnParagraphs($raw) {
        return preg_split('/'.PHP_EOL."(\s|".PHP_EOL.")*".PHP_EOL.'/', $raw);
    }
}
