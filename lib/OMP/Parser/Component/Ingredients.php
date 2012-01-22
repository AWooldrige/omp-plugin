<?php

class OMP_Parser_Component_Ingredients extends OMP_Parser_Component_Abstract {

    const SECTION_HEADER = ' Ingredients';

    /**
     * Constructor must set the name of the component
     */
    function __construct() {
        $this->componentName = 'ingredients';
    }


    /**
     * Parse the ingredients sections of the supplied text
     *
     * @param text string text to be parsed with the OMP_Parser_
     * @return array the ingredients data extracted from the raw text
     */
    public function parse($text) {
        $this->rawText = $text;
        $paragraphs = OMP_Utilities::splitOnParagraphs($text);
        foreach($paragraph as $p) {
            $lines = OMP_Utilities::splitOnNewlines($p);

            //Is the first line an Ingredients line?
            try {
                $sectionHeader = $this->parseSectionHeader($lines[0]);
                if($sectionHeader['type'] !== SECTION_HEADER) {
                    throw new InvalidArgumentException('The section header ' .
                        'parsed does not match an ' . SECTION_HEADER .
                        ' header.');
                }
            }
            catch(Exception $e) {
                $postConsumed[] = $p;
                continue;
            }
        }
    }


    /**
     * Parse ingredient line
     *
     * An ingredients line is one such as:
     * Carrots - 2 - finely diced
     *
     * @param string $line the ingredient line
     * @return array parsed ingredient line
     */
    public function parseLine($line = '') {
        $line = trim($line);
        $cols = explode(self::SEP, $line);

        for($i=0; $i<count($cols); $i++) {
            $cols[$i] = trim($cols[$i]);
            if(strlen($cols[$i]) == 0)
                throw new InvalidArgumentException('Either a blank ingredient line was provided, or a separator followed by no argument. Offending line: ' . $line);
        }

        return array(
            'name'      => (isset($cols[0])) ? $cols[0] : null,
            'quantity'  => (isset($cols[1])) ? $cols[1] : null,
            'directive' => (isset($cols[2])) ? $cols[2] : null
        );
    }



    /**
     * Parse a whole ingredients paragraph
     *
     * @param string $paragraph paragraph to parse
     * @return array the ingredients array
     */
    public function parseParagraph($paragraph) {
        $entry = array();

        $lines = explode(PHP_EOL, $paragraph);
        $headData = self::parseSectionHeader($lines[0]);

        if($headData['type'] == 'Ingredients') {
            $entry['for'] = ($headData['for'] == null) ? '_' : $headData['for'];
            $entry['items'] = array();

            for($i=1; $i<count($lines); $i++) {
                $entry['items'][] = $this->parseLine($lines[$i]);
            }

            return $entry;
        }
        else {
            throw new InvalidArgumentException('Paragraph provided does ' .
                'not appear to be an ingredients paragraph. First line: ' .
                $line);
        }
    }
}
