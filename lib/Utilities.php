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

    /**
     * Convert all line breaks into the server standard format
     *
     * @param string $raw Orignal string to convert newlines
     * @param string $break line break to use
     * @return string Parsed file with unified linebreaks
     */
    public function unifyNewlines($raw, $break = false) {
        $find = array("/(\r\n|\r|\n)/");
        $replace = ($break === false) ? PHP_EOL : $break;

        $converted = preg_replace($find, $replace, $raw);
        return $converted;
    }
}
