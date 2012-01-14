<?php
abstract class ComponentParserAbstract {

    /**
     * Parses the supplied text, returned the post consumed text. Any
     * extracted data shouldn't be returned with this.
     *
     * @param string $text recipe text to parse with ComponentParser
     * @return string the post consumed text
     */
    abstract protected function parse($text);
}
