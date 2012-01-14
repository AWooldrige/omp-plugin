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

    /**
     * Splits the recipe into paragraphs. Not bothered how many linebreaks
     * inbetween paragraphs either. Expects all linebreaks to be represented
     * by the PHP_EOL delimeter.
     *
     * @param string $raw Original recipe to split into paragraphs
     * @return array array of paragraphs
     */
    public function splitOnParagraphs($raw) {
        return preg_split('/'.PHP_EOL."(\s|".PHP_EOL.")*".PHP_EOL.'/', $raw);
    }
}
