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



}
