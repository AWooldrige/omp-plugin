<?php
abstract class ComponentParser_Abstract {

    private $rawText;

    /**
     * Parses the supplied text, returned the post consumed text. Any
     * extracted data shouldn't be returned with this.
     *
     * @param string $text recipe text to parse with ComponentParser
     * @return string the post consumed text
     */
    abstract protected function parse($text);

    /**
     * Set the raw text that the component will parse.
     *
     * @param sting $text raw text to parse
     */
    public function setRawText($text) {
        $this->rawText = $text;
    }

    /**
     * Get the raw text that the component will parse, or has parsed. The
     * parser won't touch the raw text.
     *
     * @return sting $text raw text
     */
    public function getRawText() {
        return $this->rawText;
    }


    /**
     * Examines the line given to determine if it is a header line. I.e.
     * looking something like: === Ingredients for Dish ===.
     *
     * @param string $line the line to examine as a header line
     * @return array array containing the keyword and optional second argument
     */
    public static final function parseSectionHeader($header) {
        //First if pattern matches valid === asdf ===
        $regex = '/^={1,}([a-zA-Z ]{0,})\=*$/';
        if(preg_match($regex, trim($header), $middle)) {

            //Does the middle match the "for" construct?
            if(preg_match('/([a-zA-Z]{1,})\s*for\s*([a-zA-Z ]{0,})/', trim($middle[1]), $parts)) {
                return array(
                    'type' => $parts[1],
                    'for' => $parts[2]
                );
            }

            //Is it just one word?
            $words = explode(' ', trim($middle[1]));
            if(count($words) == 1) {
                return array(
                    'type' => $words[0],
                    'for' => null
                );
            }
        }

        //If we're at this point, it didn't match any
        throw new InvalidArgumentException('Line provided does not match a header line');
    }
}
