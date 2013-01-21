<?php

class OMP_Parser_Component_Images extends OMP_Parser_Component_Abstract
{

    const SECTION_HEADER = 'Images';

    /**
     * Constructor must set the name of the component
     */
    function __construct() {
        $this->componentName = 'images';
    }

    /**
     * Parse the images sections of the supplied text
     *
     * @param text string text to be parsed with the OMP_Parser_
     * @return array the images data extracted from the raw text
     */
    public function parse($text = null) {
        if(null !== $text)
            $this->setRawText($text);

        $paragraphs = OMP_Utilities::splitOnParagraphs($this->rawText);

        //Go over each paragraph in the text provided
        foreach ($paragraphs as $p) {
            $lines = OMP_Utilities::splitOnNewlines($p);

            //Is the first line an Images line? parseSectionHeader throws
            //an exception if not a valid sectionHeaderLine
            try {
                $sectionHeader = $this->parseSectionHeader($lines[0]);

                if ($sectionHeader['type'] != self::SECTION_HEADER) {
                    throw new InvalidArgumentException(
                        'Section Header does not match an Images Header. '.
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
            //an images paragraph
            $images = array();
            for ($i=1; $i<count($lines); $i++) {
                $this->parsedData[] = $this->parseLine($lines[$i]);
            }
        }

        return $this->getParsedData();
    }

    /**
     * Parse image line
     *
     * An images line is one such as:
     * Carrots - 2 - finely diced
     *
     * @param string $line the image line
     * @return array parsed image line
     */
    public function parseLine($line = '') {
        $line = trim($line);
        $cols = explode(self::SEP, $line, 2);

        for ($i=0; $i<count($cols); $i++) {
            $cols[$i] = trim($cols[$i]);
            if (strlen($cols[$i]) == 0)
                throw new InvalidArgumentException(
                    'Either a blank image line was provided, or a ' .
                    'separator followed by no argument. Offending line: ' .
                    $line
                );
        }

        if(!is_numeric($cols[0])) {
            throw new InvalidArgumentException(
                'Attachment ID must be a positive integer. Offending line: ' .
                $line
            );
        }

        return array(
            'attachment_id' => (int) $cols[0],
            'description'  => (isset($cols[1])) ? $cols[1] : null
        );
    }
}
