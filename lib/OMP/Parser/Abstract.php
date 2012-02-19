<?php

abstract class OMP_Parser_Abstract {

    protected $rawText = null;
    protected $postConsumedText = null;
    protected $parsedData = null;
    protected $activeComponents = null;


    /**
     * Any derived parser should implement the parse method. This is the
     * director of the parsing process, and is the main entry point for class
     * users. Provides two convinence methods.
     *
     * @param string $rawText the raw recipe data to parse
     * @param array $activeComponents the active parsing components to use
     * @return array the extracted data from the raw text
     */
    abstract public function parse($rawText = null, $activeComponents = null);

    /**
     * The raw text is the raw recipe string that the parser performs on. This
     * should never be edited. TODO: Make this only setable once.
     *
     * @param string $rawText raw recipe text to parse
     */
    public function setRawText($rawText) {
        $this->rawText = $rawText;
    }
    /**
     * The raw text is the raw recipe string that the parser performs on. This
     * should never be edited.
     *
     * @return string raw recipe text that won't have been changes by the parser
     */
    public function getRawText() {
        return $this->rawText;
    }


    /**
     * The parsed data is used as a store for data the parser has extracted
     * from the raw text.
     *
     * @param array $parsedData the parsed data to store
     */
    public function setParsedData($parsedData) {
        $this->parsedData = $parsedData;
    }
    /**
     * The parsed data the parser has extracted from the raw text.
     *
     * @return array the parsed data
     */
    public function getParsedData() {
        return $this->parsedData;
    }


    /**
     * Post consumed text is any text left after the components have consumed
     * all they need to.
     *
     * @param string $postConsumbedText text left over from the components
     */
    public function setPostConsumedText($postConsumedText) {
        $this->postConsumedText = $postConsumedText;
    }
    /**
     * Post consumed text is any text left after the components have consumed
     * all they need to.
     *
     * @return string text left over from the components
     */
    public function getPostConsumedText() {
        return $this->postConsumedText;
    }


    /**
     * The active components specify which components the parser should use to
     * parse the recipe. They are specifier as an array of strings. Each string
     * should reference the last part of a Component class. I.e.
     *
     * 'Ingredients' would refer to the class OMP_Parser_Component_Ingredients
     *
     * @param array $component array of strings specifying active components
     */
    public function setActiveComponents($activeComponents) {
        /**
         * Eventually, should probably check if a component actually exists,
         * at the moment we PHP Fatal.
         */
        $this->activeComponents = $activeComponents;
    }
    /**
     * The active components specify which components the parser should use to
     * parse the recipe. They are specifier as an array of strings. Each string
     * should reference the last part of a Component class. I.e.
     *
     * 'Ingredients' would refer to the class OMP_Parser_Component_Ingredients
     *
     * @return array of strings specifying active components
     */
    public function getActiveComponents() {
        return $this->activeComponents;
    }

    /**
     * Convert and return the contents of parsedData in JSON format
     *
     * @return string JSON representation of parsedData
     */
    public function getParsedDataAsJson() {
        $json = new Zend_Json();
        return $json->encode($this->parsedData);
    }
}
