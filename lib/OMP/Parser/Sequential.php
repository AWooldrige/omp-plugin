<?php

/**
 * The sequential parser takes each component in turn, and passes the remaining
 * text from the previous component, into the input of the next. In a chaining
 * style.
 */
class OMP_Parser_Sequential extends OMP_Parser_Abstract {
    public function parse($rawText = null, $activeComponents = null) {
        if((null !== $rawText))
            $this->rawText = $rawText;
        if(null !== $activeComponents)
            $this->activeComponents = $activeComponents;

        $tmpData = array();
        $tmpText = $this->rawText;
        foreach($this->activeComponents as $c) {
            $componentName = 'OMP_Parser_Component_' . $c;
            $component = new $componentName();
            $component->setRawText($tmpText);
            $component->parse();

            $tmpText = $component->getPostConsumedText();
            $tmpData[$c] = $component->getParsedData();
        }

        $this->parsedData = $tmpData;
        $this->postConsumedText = $tmpText;

        return $this->parsedData;
    }
}
