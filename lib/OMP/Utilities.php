<?php
class OMP_Utilities {

    /**
     * Splits this supplied text into an array, with each element containing
     * one paragraph. Expects all linebreaks to be represented by the PHP_EOL
     * delimeter.
     *
     * @param string $raw string to split into paragraphs
     * @return array array of paragraphs
     */
    public static function splitOnParagraphs($raw) {
        return preg_split('/'.PHP_EOL."(\s|".PHP_EOL.")*".PHP_EOL.'/', $raw);
    }

    /**
     * Convert all line breaks into the server standard format
     *
     * @param string $raw Orignal string to convert newlines
     * @param string $break line break to use
     * @return string Parsed file with unified linebreaks
     */
    public static function unifyNewlines($raw, $break = false) {
        $find = array("/(\r\n|\r|\n)/");
        $replace = ($break === false) ? PHP_EOL : $break;

        $converted = preg_replace($find, $replace, $raw);
        return $converted;
    }


    /**
     * Splits line breaks into seperate array entries. If there are no new
     * linebreaks in the string, an array with one element is still returned.
     * Expects unified new lines.
     *
     * @param string $raw text to split new lines
     * @return array array of each line of the $raw
     */
    public static function splitOnNewlines($raw) {
        return split(PHP_EOL, $raw);
    }

    /**
     * Convert array of lines back into plain text with new lines
     *
     * @param array $array array of new lines
     * @return string merged into a string
     */
    public static function mergeOnNewlines($array) {
    }

    /**
     * Convert an array of paragraphs back into plain text with double line
     * breaks.
     *
     * @param array $array of paragraphs to merge
     * @return string merged paragraphs
     */
    public static function mergeOnParagraphs($array) {
    }
}
