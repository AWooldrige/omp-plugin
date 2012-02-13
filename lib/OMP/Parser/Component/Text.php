<?php

class OMP_Parser_Component_Text extends OMP_Parser_Component_Abstract {

    const SECTION_HEADER = 'Text';

    /**
     * Constructor must set the name of the component
     */
    function __construct() {
        $this->componentName = 'Text';
    }

    /**
     * Parse the text section of the supplied text.
     *
     * @param text string text to be parsed
     * @return array the text data extracted from the raw text
     */
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $data = array();
        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);

        foreach($paragraphs as $p) {
        }

        $this->parsedData = $data;
        return $this->getParsedData();
    }
}
