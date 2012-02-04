<?php
/**
 * Generic List Parser
 *
 * Parses a markdown style list into PHP array.
 */
class OMP_Parser_Generic_List {

    private $rawText = null;
    private $itemSpecifier = null;

    /**
     * Constructor
     *
     * @param string $list optionally specify a the list text to parse
     * @param string $itemIdent optionally specify item identifier
     */
    public function __construct($rawText = null, $itemSpecifier = null) {
        if($rawText !== null)
            $this->setRawText($rawText);

        if($itemSpecifier !== null)
            $this->setItemSpecifier($itemSpecifier);
    }

    /**
     * Set the raw text for the parser to use
     *
     * @param string $rawText the raw text to parse
     */
    public function setRawText($rawText) {
        $this->rawText = $rawText;
    }
    /**
     * Get the raw text that the parser used (it won't have been modified
     * by the parser in any way).
     *
     * @return string the raw text
     */
    public function getRawText() {
        return $this->rawText;
    }

    /**
     * Get the item specifier
     *
     * The item specifier is used to specify an item in the list. E.g.
     *  - Item 1
     *  - Item 2
     * Where the specifier is -
     *
     * @param string $itemSpecifier the item specifier
     */
    public function setItemSpecifier($itemSpecifier) {
        $this->itemSpecifier = $itemSpecifier;
    }
    /**
     * Get the item specifier
     *
     * The item specifier is used to specify an item in the list. E.g.
     *  - Item 1
     *  - Item 2
     * Where the specifier is -
     *
     * @return string the raw text
     */
    public function getItemSpecifier() {
        return $this->itemSpecifier;
    }

    /**
     * Parse the text provided, either by parameter or what is in rawText.
     *
     * @param string $rawText raw text to parse
     * @param string $itemSpecifier the item specifier
     * @return array the extracted data
     */
    public function parse($rawText = null, $itemSpecifier = null) {
        //Need to initialise?
        if($rawText !== null)
            $this->setRawText($rawText);
        if($itemSpecifier !== null)
            $this->setItemSpecifier($itemSpecifier);

        //Parse all lines at once
        $parsedLines = array();
        foreach(OMP_Utilities::splitOnNewlines($this->getRawText()) as $line) {
            $parsedLines[] = $this->parseLine($line);
        }


        //Convert to an array
        return $this->generateArray(
            $this->mergeContinuationLines($parsedLines));
    }


    /**
     * Parse an item line, return an array of the following schema
     *
     * array(
     *      //Start of new bullet, or continuing text from the last?
     *     'lineType'             => ('new'|'continuation'),
     *
     *      //No. spaces that the first character is indented by
     *     'specifierIndentLevel' => (null|(0:INF)),
     *
     *       //Raw Content of the line
     *     'content'              => (null|string)
     * );
     *
     *  - This line would produce the following:
     *
     * array(
     *     'lineType'             => 'new',
     *     'specifierIndentLevel' => 1,
     *     'content'              => 'This line would produce the following:'
     * );
     *
     * @param string $rawLine the raw line to parse for information
     * @param string $itemSpecifier the item specifier
     * @return array the information array specified above
     */
    public function parseLine($rawLine = null) {
        if(strlen($this->getItemSpecifier() > 1)) {
            throw new InvalidArgumentException('The this->getItemSpecifier() cannot be more than one character: ' . $this->getItemSpecifier());
        }

        //Avoiding the regex!

        //Go through each character from the start
        for($i = 0; $i < strlen($rawLine); $i++) {

            //Local copy, for ease
            $c = $rawLine[$i];

            //If it is whitespace (tab or space)
            if(ctype_space($c) == true) {
                //increment indent counter, continue
                continue;
            }

            //is it item specifier?
            if($c == $this->getItemSpecifier()) {

                //trim the rest of the string
                $rt = trim(substr($rawLine, $i+1, strlen($rawLine)));

                //if the strlen is < 0, exception
                if(strlen($rt) < 1) {
                    throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
                }

                return array(
                    'lineType'             => 'new',
                    'specifierIndentLevel' => $i,
                    'content'              => $rt
                );
            }

            //trim the whole string
            $rt = trim($rawLine);

            //if the strlen is < 0, exception
            if(strlen($rt) < 1) {
                throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
            }

            return array(
                'lineType'             => 'continuation',
                'specifierIndentLevel' => null,
                'content'              => $rt
            );
        }

        //exception
        throw new InvalidArgumentException('Could not process line, it appers to be empty: ' . $rawLine);
        return null;
    }


    /**
     * Merge Continuation Lines
     *
     * Any lines not starting with an item specifier are assumed to be
     * continuations of the previous item. Because of this append it to the
     * previous content, joined with a space for good measure.
     *
     * @param array $flatArray array to merge continuation lines on
     * @return array with the continuation lines merged
     */
    public function mergeContinuationLines($flatArray) {

        $merged = array();
        $isFirst = true;

        //Merge all continuations
        foreach($flatArray as $line) {
            //Is the first line a continuation line?
            if(($line['lineType'] == 'continuation') && $isFirst)
                throw new InvalidArgumentException('The first line of a list cannot be a continuation. I.e. The first line must begin with a "' . $this->getItemSpecifier() . '".');


            //Add to the array if its a new item
            if($line['lineType'] == 'new') {
                $merged[] = array(
                    'specifierIndentLevel' => $line['specifierIndentLevel'],
                    'content'              => $line['content']
                );

                //We're no longer on the first item
                $isFirst = false;
                continue;
            }

            //We've got a continuation line, append to previous' content
            $merged[count($merged) - 1]['content'] .= ' ' . $line['content'];
        }

        return $merged;
    }


    /**
     * Generate Structured Array
     *
     * Converts the flat array style generated within parse() using
     * parseLine() to a nested array structure, removing the need for
     * itemSpecifierIndent. Uses recursion, but oh well.
     *
     * @param array $items the flat array to convert
     * @return array the nested structure of $items
     */
    public function generateArray($items) {

        //Initialise empty array (this gets returned as null if empty)
        $list = array();
        $numItems = count($items);

        //Work out the indentation of the first item
        $level = $items[0]['specifierIndentLevel'];

        $i = 0;
        while($i < $numItems) {

            //Induction base case: Item has no subitems
            $iNext = $i + 1;

            //If we're falling off the end of the array
            if($iNext >= $numItems) {
                $list[] = array(
                    'item' => $items[$i]['content'],
                    'subitems' => null
                );
                $i++;
                continue;
            }

            //If the next item is of less indentation
            if($items[$iNext]['specifierIndentLevel'] <= $level) {
                $list[] = array(
                    'item' => $items[$i]['content'],
                    'subitems' => null
                );
                $i++;
                continue;
            }



            //Item has subitems: Generate a new list, of the subitems
            $subitems = array();
            $fastForwardTo = $numItems;
            for($s = $iNext; $s < $numItems; $s++) {
                if(($items[$s]['specifierIndentLevel'] <= $level)) {
                    $fastForwardTo = $s;
                    break;
                }

                $subitems[] = $items[$s];
            }

            //Recursive call on the subitems
            $list[] = array(
                'item' => $items[$i]['content'],
                'subitems' => $this->generateArray($subitems)
            );

            $i = $fastForwardTo;
        }

        return (count($list)>0) ? $list : null;
    }
}
