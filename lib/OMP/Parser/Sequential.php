<?php

/**
 * The sequential parser takes each component in turn, and passes the remaining
 * text from the previous component, into the input of the next. In a chaining
 * style.
 */
class OMP_Parser_Sequential extends OMP_Parser_Abstract {

    /**
     * Sequentially parse the text within rawText, using the components
     * specified by activeComponents. The sequential parser chains the
     * components, sending the output of the previous, as the output of the next
     *
     * @param string rawText raw text to parse
     * @param array activeComponents array of strings of active component names
     * @return array the parsed data
     */
    public function parse($rawText = null, $activeComponents = null) {
        if((null !== $rawText))
            $this->rawText = $rawText;
        if(null !== $activeComponents)
            $this->activeComponents = $activeComponents;

        $tmpData = array();
        $tmpText = $this->rawText;

        //Chain the ouput of each component
        foreach($this->activeComponents as $c) {
            $componentName = 'OMP_Parser_Component_' . $c;
            $component = new $componentName();
            $component->setRawText($tmpText);
            $component->parse();

            $tmpText = $component->getPostConsumedText();
            $tmpData[$c] = $component->getParsedData();
        }

        $this->parsedData = $tmpData;
        $this->postConsumedText = (strlen(trim($tmpText)) === 0) ?
                                  null : $tmpText;

        return $this->parsedData;
    }
}
