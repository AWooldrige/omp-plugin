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


    /**
     * Examines the line given to determine if it is a header line. I.e.
     * looking something like: === Ingredients for Dish ===.
     *
     * @param string $line the line to examine as a header line
     * @return array array containing the keyword and optional second argument
     */
    public function pSectionHeader($header) {
        //First if pattern matches valid === asdf ===
        $regex = '/^={1,}([a-zA-Z ]{0,})\=*$/';
        if(preg_match($regex, trim($header), $middle)) {

            //Does the middle match the "for" construct?
            if(preg_match('/([a-zA-Z]{1,})\s*for\s*([a-zA-Z ]{0,})/', trim($middle[1]), $parts)) {
                return array(
                    'type' => $parts[1],
                    'for' => $parts[2]
                );
            }

            //Is it just one word?
            $words = explode(' ', trim($middle[1]));
            if(count($words) == 1) {
                return array(
                    'type' => $words[0],
                    'for' => null
                );
            }
        }

        //If we're at this point, it didn't match any
        throw new InvalidArgumentException('Line provided does not match a header line');
    }



    public function pIngredientsParagraph($paragraph) {
        $entry = array();

        $lines = explode(PHP_EOL, $paragraph);
        $headData = $this->pSectionHeader($lines[0]);

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
