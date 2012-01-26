<?php
abstract class OMP_Parser_Component_Abstract {

    const SEP = '-';

    protected $componentName = null;
    protected $rawText = null;
    protected $postConsumed = array();
    protected $parsedData = array();

    /**
     * Parses the supplied text, returning any extracted data. Text left
     * over that was not consumed by the parse is available from:
     * getPostconsumedText()
     *
     * @param string $text recipe text to parse with OMP_Parser_Component
     * @return array the data extracted from the parsing
     */
    abstract protected function parse($text);

    /**
     * Set the raw text that the component will parse.
     *
     * @param string $text raw text to parse
     */
    public function setRawText($text) {
        $this->rawText = $text;
    }

    /**
     * Get the raw text that the component will parse, or has parsed. The
     * parser won't touch the raw text.
     *
     * @return string $text raw text
     */
    public function getRawText() {
        return $this->rawText;
    }

    /**
     * Get the post consumed text. I.e. The text that remains after the
     * implemented component parsers consumed the text it is responsible for.
     *
     * @return string the post consumed text
     */
    public function getPostConsumedText() {
        return OMP_Utilities::mergeOnParagraphs($this->postConsumed);
    }

    /**
     * Get the parsed data.
     *
     * @return string the parsed data
     */
    public function getParsedData() {
        return $this->parsedData;
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
        throw new InvalidArgumentException('Line provided does not match a header line: ' . $header);
    }


    /**
     * Return the name of the component. I .e. 'ingredients'
     *
     * @return string name of the component
     */
    public function getComponentName() {
        if($this->componentName == null) {
            throw new DomainException('Component has not redifined its name.');
        }

        return $this->componentName;
    }


    /**
     * Set the name of the component. I.e. 'ingredients'. This probably
     * shouldn't exist, but it makes testing somewhat easier.
     *
     * @param name string name of the component
     */
    public function setComponentName($name) {
        $this->componentName = $name;
    }
}
