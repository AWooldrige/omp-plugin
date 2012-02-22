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
            $meta = array();
            for($i=1; $i<count($lines); $i++) {
                $meta[] = $this->parseLine($lines[$i]);
            }

        }

        return $this->getParsedData();
    }


    /**
     * Parse meta line
     *
     * A meta line is one such as:
     * Active Time - 20m
     *
     * @param string $line the meta line
     * @return array parsed meta line
     */
    public function parseLine($line = '') {
        $line = trim($line);
        $cols = explode(self::SEP, $line);

        for($i=0; $i<count($cols); $i++) {
            $cols[$i] = trim($cols[$i]);
            if(strlen($cols[$i]) == 0)
                throw new InvalidArgumentException('Either a blank meta line was provided, or a separator followed by no argument. Offending line: ' . $line);
        }

    }

    /**
     * Normalise meta name
     * E.g. acTive TiMe -> activeime
     */
    public function normaliseMetaName($name) {
        //trim
        //convert to lowercase
        //replace all spaces with underscore
        $normalised = preg_replace('/\s+/', '_', strtolower(trim($name)));

        //Check valid name
        if(!OMP_Utilities::isPhpLabelValid($normalised)) {
            throw new InvalidArgumentException('Meta Name "'.$name.'", is '.
                'not valid as a standard PHP label (when normalised to "'.
                $normalised.'").');
        }

        return $normalised;
    }
}
