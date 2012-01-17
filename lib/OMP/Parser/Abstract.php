<?php

abstract class OMP_Parser_Abstract {
    private $raw = null;
    private $consumable = null;
    private $cooked = null;

    abstract public function parse();

}
