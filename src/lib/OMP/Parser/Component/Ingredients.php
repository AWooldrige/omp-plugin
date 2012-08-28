<?php

class OMP_Parser_Component_Ingredients extends OMP_Parser_Component_Abstract
{

    const SECTION_HEADER = 'Ingredients';

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
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);

        //Go over each paragraph in the text provided
        foreach ($paragraphs as $p) {
            $lines = OMP_Utilities::splitOnNewlines($p);

            //Is the first line an Ingredients line? parseSectionHeader throws
            //an exception if not a valid sectionHeaderLine
            try {
                $sectionHeader = $this->parseSectionHeader($lines[0]);

                if ($sectionHeader['type'] != self::SECTION_HEADER) {
                    throw new InvalidArgumentException(
                        'Section Header does not match an Ingredients Header. '.
                        'Looking for: ' .  self::SECTION_HEADER.' in line: ' .
                        $lines[0]
                    );
                }
            }
            catch(InvalidArgumentException $e) {
                $this->postConsumed[] = $p;
                continue;
            }

            //Can assume at this point that this paragraph is an attempt at
            //an ingredients paragraph
            $ingredients = array();
            for ($i=1; $i<count($lines); $i++) {
                $ingredients[] = $this->parseLine($lines[$i]);
            }
            $this->appendParsedData($sectionHeader['for'], $ingredients);
        }

        return $this->getParsedData();
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
        $cols = explode(self::SEP, $line, 3);

        for ($i=0; $i<count($cols); $i++) {
            $cols[$i] = trim($cols[$i]);
            if (strlen($cols[$i]) == 0)
                throw new InvalidArgumentException(
                    'Either a blank ingredient line was provided, or a ' .
                    'separator followed by no argument. Offending line: ' .
                    $line
                );
        }

        return array(
            'name'      => (isset($cols[0])) ? $cols[0] : null,
            'quantity'  => (isset($cols[1])) ? $cols[1] : null,
            'directive' => (isset($cols[2])) ? $cols[2] : null
        );
    }


    /**
     * Add entry to parsedData. This checks to make that an entry with that
     * name doesn't already exist. A null value for the $for parameter will
     * be converted to the string '_'.
     *
     * @param $component string specified by the for construct
     * @param $ingredients array list of ingredients
     */
    public function appendParsedData($component, $ingredients) {
        if ($component == null) {
            $component = '_';
        }

        if (array_key_exists($component, $this->parsedData)) {
            throw new InvalidArgumentException('Two Ingredients sections ' .
                'for the same "'.$component.' component have been specified');
        }

        $this->parsedData[$component] = $ingredients;
    }
}
