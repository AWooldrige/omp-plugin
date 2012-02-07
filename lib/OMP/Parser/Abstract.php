<?php

abstract class OMP_Parser_Abstract {
    protected $rawText = null;
    protected $postConsumedText = null;
    protected $parsedData = null;

    abstract public function parse();

    public function setRawText($rawText) {
        $this->rawText = $rawText;
    }
    public function getRawText() {
        return $this->rawText;
    }

    public function setParsedData($parsedData) {
        $this->parsedData = $parsedData;
    }
    public function getParsedData() {
        return $this->parsedData;
    }

    public function setPostConsumedText($postConsumedText) {
        $this->postConsumedText = $postConsumedText;
    }
    public function getPostConsumedText() {
        return $this->postConsumedText;
    }
}
