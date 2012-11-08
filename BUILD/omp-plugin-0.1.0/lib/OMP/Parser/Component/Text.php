<?php

class OMP_Parser_Component_Text extends OMP_Parser_Component_Abstract
{

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
     * The first paragraph of the recipe is considered to be the summary text
     * ("more" text in WordPress). Any subsequent paragraphs throughout the
     * entire recipe are considered plain content.
     *
     * @param text string text to be parsed
     * @return array the text data extracted from the raw text
     */
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $data = array();
        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);

        $pParas = array();

        foreach ($paragraphs as $p) {
            $lines = OMP_Utilities::splitOnNewlines($p);

            //See if this paragraph contains a section header
            try {
                //Its a section header, ignore
                $sectionHeader = $this->parseSectionHeader($lines[0]);
                $this->postConsumed[] = $p;
                continue;
            }
            catch(InvalidArgumentException $e) {
                $pParas[] = $p;
            }
        }

        if (count($pParas) == 0) {
            return null;
        }

        //Merge all manual linebreaks
        for ($i = 0; $i < count($pParas); $i++) {
            $pParas[$i] = OMP_Utilities::mergeManualLinebreaks($pParas[$i]);
        }

        $this->parsedData = array(
            'summary' => $pParas[0],
            'other' => (count($pParas) > 1) ? OMP_Utilities::mergeOnParagraphs(
                    array_slice($pParas, 1)
                ) : null
        );
        return $this->getParsedData();
    }
}
