<?php

class OMP_Parser_Component_Meta extends OMP_Parser_Component_Abstract {

    const SECTION_HEADER = 'Meta';

    /**
     * Constructor must set the name of the component
     */
    function __construct() {
        $this->componentName = 'meta';
    }


    /**
     * Parse the meta section of the supplied text
     *
     * @param text string text to be parsed with the OMP_Parser_
     * @return array the meta data extracted from the raw text
     */
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);

        //Go over each paragraph in the text provided
        foreach($paragraphs as $p) {
            $lines = OMP_Utilities::splitOnNewlines($p);

            //Is the first line an Ingredients line? parseSectionHeader throws
            //an exception if not a valid sectionHeaderLine
            try {
                $sectionHeader = $this->parseSectionHeader($lines[0]);

                if($sectionHeader['type'] != self::SECTION_HEADER) {
                    throw new InvalidArgumentException('Section Header does not match a Meta Header. Looking for: '.self::SECTION_HEADER.' in line: ' . $lines[0]);
                }
            }
            catch(InvalidArgumentException $e) {
                $this->postConsumed[] = $p;
                continue;
            }

            //Can assume at this point that this paragraph is an attempt at
            //a meta paragraph

        }

        return $this->getParsedData();
    }
}
