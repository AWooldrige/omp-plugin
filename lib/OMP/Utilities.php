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
     * @param array $lines array of new lines to merge
     * @return string merged into a string
     */
    public static function mergeOnNewlines($lines) {

        //TODO: Could this be done with implode?
        $merged = '';
        $first = true;

        foreach($lines as $p) {
            if(!$first) {
                $merged .= PHP_EOL;
            }

            $first = false;
            $merged .= $p;
        }

        return $merged;
    }

    /**
     * Convert an array of paragraphs back into plain text with double line
     * breaks.
     *
     * @param array $paragraphs array of paragraphs to merge
     * @return string merged paragraphs
     */
    public static function mergeOnParagraphs($paragraphs) {

        $merged = '';
        $first = true;

        foreach($paragraphs as $p) {
            if(!$first) {
                $merged .= PHP_EOL . PHP_EOL;
            }

            $first = false;
            $merged .= $p;
        }

        return $merged;
    }


    /**
     * Convert a string provided that consists of multiple lines, into a string
     * which joins the new lines separated with a space. Note that this is not
     * context aware, i.e. it will merge any newline it encounters. No detection
     * for section headers of list items etc occurs. It can handle multiple
     * paragraphs.
     *
     * Paragraphs with manual
     * line breaks should be merged
     * into one line.
     *
     * Paragraphs with manual line breaks should be merged into one line.
     *
     * @param string $rawText
     * @return string string with merged manual linebreaks
     */
    public static function mergeManualLinebreaks($rawText) {
    }

}
