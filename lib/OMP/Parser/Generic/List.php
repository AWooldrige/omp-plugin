<?php
/**
 * Generic List Parser
 *
 * Parses a markdown style list into PHP array.
 */
class OMP_Parser_Generic_List {

    private $rawText = null;
    private $itemSpecifier = null;

    /**
     * Constructor
     *
     * @param string $list optionally specify a the list text to parse
     * @param string $itemIdent optionally specify item identifier
     */
    public function __construct($rawText = null, $itemSpecifier = null) {
        if($rawText !== null)
            $this->setRawText($rawText);

        if($itemSpecifier !== null)
            $this->setItemSpecifier($itemSpecifier);
    }

    /**
     * Set the raw text for the parser to use
     *
     * @param string $rawText the raw text to parse
     */
    public function setRawText($rawText) {
        $this->rawText = $rawText;
    }
    /**
     * Get the raw text that the parser used (it won't have been modified
     * by the parser in any way).
     *
     * @return string the raw text
     */
    public function getRawText() {
        return $this->rawText;
    }

    /**
     * Get the item specifier
     *
     * The item specifier is used to specify an item in the list. E.g.
     *  - Item 1
     *  - Item 2
     * Where the specifier is -
     *
     * @param string $itemSpecifier the item specifier
     */
    public function setItemSpecifier($itemSpecifier) {
        $this->itemSpecifier = $itemSpecifier;
    }
    /**
     * Get the item specifier
     *
     * The item specifier is used to specify an item in the list. E.g.
     *  - Item 1
     *  - Item 2
     * Where the specifier is -
     *
     * @return string the raw text
     */
    public function getItemSpecifier() {
        return $this->itemSpecifier;
    }

    /**
     * Parse the text provided, either by parameter or what is in rawText.
     *
     * @param string $rawText raw text to parse
     * @param string $itemSpecifier the item specifier
     * @return array the extracted data
     */
    public function parse($rawText = null, $itemSpecifier = null) {
        if($rawText !== null)
            $this->setRawText($rawText);

        if($itemSpecifier !== null)
            $this->setItemSpecifier($itemSpecifier);


        return array();
    }

    /**
     * Parse an item line, return an array of the following schema
     *
     * array(
     *      //Start of new bullet, or continuing text from the last?
     *     'lineType'             => ('new'|'continuation'),
     *
     *      //No. spaces that the first character is indented by
     *     'specifierIndentLevel' => (null|(0:INF)),
     *
     *       //Raw Content of the line
     *     'content'              => (null|string)
     * );
     *
     *  - This line would produce the following:
     *
     * array(
     *     'lineType'             => 'new',
     *     'specifierIndentLevel' => 1,
     *     'content'              => 'This line would produce the following:'
     * );
     *
     * @param string $rawLine the raw line to parse for information
     * @param string $itemSpecifier the item specifier
     * @return array the information array specified above
     */
    public function parseLine($rawLine = null, $itemSpecifier = null) {
        if(strlen($itemSpecifier) > 1) {
            throw new InvalidArgumentException('The itemSpecifier cannot be more than one character: ' . $itemSpecifier);
        }

        //Avoiding the regex!

        //Go through each character from the start
        for($i = 0; $i < strlen($rawLine); $i++) {

            //Local copy, for ease
            $c = $rawLine[$i];

            //If it is whitespace (tab or space)
            if(ctype_space($c) == true) {
                echo '$c at the whitespace check: ' . $c;
                //increment indent counter, continue
                continue;
            }

            //is it item specifier?
            //THIS ISN'T WORKING
            echo '$c = ' . $c;
            echo '$itemSpecifier = ' .$itemSpecifier;
            if($c == $itemSpecifier) {

                //trim the rest of the string
                $rt = trim(substr($rawLine, $i+1, strlen($rawLine)));

                //if the strlen is < 0, exception
                if(strlen($rt) < 1) {
                    throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
                }

                return array(
                    'lineType'             => 'new',
                    'specifierIndentLevel' => $i,
                    'content'              => $rt
                );
            }

            //trim the whole string
            $rt = trim($rawLine);

            //if the strlen is < 0, exception
            if(strlen($rt) < 1) {
                throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
            }

            return array(
                'lineType'             => 'continuation',
                'specifierIndentLevel' => null,
                'content'              => $rt
            );
        }

        //exception
        throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
        return null;
    }


}
