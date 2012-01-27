<?php

class OMP_Parser_Component_Method extends OMP_Parser_Component_Abstract {

    const SECTION_HEADER = 'Method';

    /**
     * Constructor must set the name of the component
     */
    function __construct() {
        $this->componentName = 'method';
    }


    /**
     * Parse the method section of the supplied text
     *
     * @param text string text to be parsed with the OMP_Parser_
     * @return array the method data extracted from the raw text
     */
    public function parse($text) {
        $this->rawText = $text;

        $paragraphs = OMP_Utilities::splitOnParagraphs($text);

    }
}
