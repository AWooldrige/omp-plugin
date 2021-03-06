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
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $data = array();
        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);
        $methodFound = false;

        //Can only have one method paragraph
        foreach($paragraphs as $p) {
              $lines = OMP_Utilities::splitOnNewlines($p);

              try {
                  $sectionHeader = $this->parseSectionHeader($lines[0]);

                  if($sectionHeader['type'] != self::SECTION_HEADER) {
                      throw new InvalidArgumentException('Section Header does not match an Method Header. Looking for: '.self::SECTION_HEADER.' in line: ' . $lines[0]);
                  }
              }
              catch(InvalidArgumentException $e) {
                  $this->postConsumed[] = $p;
                  continue;
              }

            if(!$methodFound) {
                //Can assume at this point that this paragraph is an attempt at
                //an ingredients paragraph
                $list = new OMP_Parser_Generic_List(
                    OMP_Utilities::mergeOnNewlines(array_slice($lines, 1)), '-');
                $methodFound = true;
                $data = $list->parse();
            }
            else {
                throw new InvalidArgumentException('Only one method section is allowed per recipe');
            }
        }
        $this->parsedData = $data;
        return $this->getParsedData();
    }
}
