<?php

abstract class Parser_Abstract {
    private $raw = null;
    private $consumable = null;
    private $cooked = null;

    abstract public function parse();

}
