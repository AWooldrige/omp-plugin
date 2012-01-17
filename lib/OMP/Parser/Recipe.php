<?php
require_once('lib/Parser/Abstract.php');

class Parser_Recipe extends Parser_Abstract {
    const SEP = '-';

    private $raw;
    private $paras;
    private $pParsers = array(
        'pIngredientParagraph',
        'pMethodParagraph');

    private $cooked = array(
        'ingredients' => null,
        'method' => null,
        'summary' => null,
        'tips' => null,
        'text' => array(
            'more-text' => null,
            'other' => null),
        'rating' => null,
        'active-time' => null,
        'passive-time' => null,
        'cost-per-portion' => null,
        'difficulty' => null);


    /**
     * Constructor
     * @param $raw string containing the raw unparsed recipe
     */
    function __construct($raw = '') {
        $this->raw = $raw;
    }


    /**
     * Parse the raw into the cooked.
     *
     * Iterate over each paragraph, attmept to remove it.
     */
    public function parse() {

        //Split into paragraphs
        $this->paras = $this->splitParagraphs(unifyNewLines($this->raw));

        $detectMoreText = true;

        //Iterate over each paragraph, attempt to convert parse using
        //each registered function
        foreach($this->paras as $p) {
            foreach($this->registered as $f) {
                try {
                    $this->$f($p);
                    $detectMoreText = false;
                    break;
                }
                catch(Exception $e) {
                    continue;
                }
            }
            if($detectMoreText) {
                $this->cooked['text']['more-text'][] = $p;
                break;
            }

            $this->cooked['text']['other'][] = $p;
        }

        $ingredients = pIngredientSections($this->paras);
        var_dump($ingredients);
    }


    public function pIngredientsParagraph($paragraph) {
        $entry = array();

        $lines = explode(PHP_EOL, $paragraph);
        $headData = OMP_Utilities::parseSectionHeader($lines[0]);

        if($headData['type'] == 'Ingredients') {
            $entry['for'] = ($headData['for'] == null) ? '_' : $headData['for'];
            $entry['items'] = array();

            for($i=1; $i<count($lines); $i++) {
                $entry['items'][] = $this->pIngredientLine($lines[$i]);
            }

            return $entry;
        }
        else {
            throw new InvalidArgumentException('Either a blank ingredient line was provided, or a separator followed by no argument. Offending line: ' . $line);
        }
    }

    /**
     * Parse Ingredient line
     *
     * @param string $line the ingredient line
     * @param bool $convertQuantity
     *      whether to convert the quantity field using Zend_Measure
     * @return array parsed ingredient line
     */
    public function pIngredientLine($line = '', $convertQuantity = true) {
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


    public function setRaw($raw) {
        $this->raw = $raw;
    }
    public function getRaw() {
        return $this->raw;
    }

    public function getCooked() {
        return $this->getCooked();
    }
}
