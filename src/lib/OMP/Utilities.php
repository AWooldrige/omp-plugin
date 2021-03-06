<?php
class OMP_Utilities {

    /**
     * Splits this supplied text into an array, with each element containing
     * one paragraph. Expects all linebreaks to be represented by the PHP_EOL
     * delimeter.
     *
     * Note: Expects newlines to be unified.
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
     * Note: Expects newlines to be unified.
     *
     * @param string $raw text to split new lines
     * @return array array of each line of the $raw
     */
    public static function splitOnNewlines($raw) {
        $lines = split(PHP_EOL, $raw);

        //Need to remove any lines that just contain whitespace
        $toReturn = array();
        foreach($lines as $l) {
            if(strlen(trim($l)) > 0) {
                $toReturn[] = $l;
            }
        }

        return $toReturn;
    }

    /**
     * Convert array of lines back into plain text with new lines
     *
     * Note: Expects newlines to be unified.
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
     * Note: Expects newlines to be unified.
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
     * Note: Expects newlines to be unified.
     *
     * @param string $rawText the raw text to merge
     * @param string $delimited optional delimiter to use, defaults to space
     * @return string string with merged manual linebreaks
     */
    public static function mergeManualLinebreaks($rawText, $delimiter = null) {

        $paras = self::splitOnParagraphs($rawText);
        $replace = (null === $delimiter) ? ' ' : $delimiter;

        for($i = 0; $i < count($paras); $i++) {
            $lines = self::splitOnNewlines($paras[$i]);
            $paras[$i] = '';
            $first = true;
            foreach($lines as $l) {
                if($first) {
                    $paras[$i] = trim($l);
                    $first = false;
                    continue;
                }
                $paras[$i] .= $replace . trim($l);
            }
        }

        return self::mergeOnParagraphs($paras);
    }

    /**
     * Determines whether the provided string provided is acceptable as a label
     * within PHP. I.e. a function of variable name
     *
     * @param string $raw the label to check
     * @return boolean whether valid or not
     */
    public static function isPhpLabelValid($raw) {
        if(preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $raw)) {
            return true;
        } else {
            return false;
        }
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
