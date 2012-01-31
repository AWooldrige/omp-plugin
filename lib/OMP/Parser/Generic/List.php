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
        return array();
    }

}
