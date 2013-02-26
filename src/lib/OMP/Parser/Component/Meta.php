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
        $paraFound = false;

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

            if($paraFound) {
                throw new InvalidArgumentException('Only one meta section is allowed per recipe');
            }

            //Can assume at this point that this paragraph is an attempt at
            //a meta paragraph
            $meta = array();
            for($i=1; $i<count($lines); $i++) {

                $line = $this->parseLine($lines[$i]);

                //Can we find a class method to process this meta field further?
                $functionName = 'parseMeta_'.$line['name'];
                if(method_exists(__CLASS__, $functionName)) {
                    $meta[$line['name']] = $this->$functionName($line['details']);
                }
                else {
                    $meta[$line['name']] = $line['details'];
                }
            }

            $this->parsedData = $meta;
            $paraFound = true;
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
     * @return array the normalised meta field, and raw data
     */
    public function parseLine($line = '') {
        $line = trim($line);
        if(strlen($line) <= 0) {
            throw new InvalidArgumentException('Meta line cannot be blank');
        }

        $cols = explode(self::SEP, $line, 2);

        if(count($cols) !== 2) {
            throw new InvalidArgumentException('The meta line "'.$line.'" '.
                'cannot be interpreted. Most likely because the separator "'.
                self::SEP.'" cannot be found in the line.');
        }

        $cols[0] = trim($cols[0]);
        $cols[1] = trim($cols[1]);

        if((strlen($cols[0]) <= 0) || (strlen($cols[1]) <= 0)) {
            throw new InvalidArgumentException('The meta line "'.$line.'" '.
                'is missing either the first or second argument');
        }

        return array('name' => $metaName = self::normaliseMetaName($cols[0]),
                     'details' => $cols[1]);
    }


    /**
     * Normalise meta name
     * E.g. acTive TiMe -> activeime
     */
    public static function normaliseMetaName($name) {
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



    /**
     * Below are all the possible meta fields that are parseable
     */

    /**
     * Parse the active_time meta field
     *
     * @param string $raw the raw data provided to the meta field
     * @return mixed data extracted from the meta field.
     */
    private function parseMeta_cook_time($raw) {
        return OMP_Utilities::convertHumanDurationToIso8601($raw);
    }
    /**
     * Parse the inactive_time meta field
     *
     * @param string $raw the raw data provided to the meta field
     * @return mixed data extracted from the meta field.
     */
    private function parseMeta_prep_time($raw) {
        return OMP_Utilities::convertHumanDurationToIso8601($raw);
    }
}
