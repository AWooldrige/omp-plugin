<?php

class OMP_Parser_Generic_ListTest extends PHPUnit_Framework_TestCase {

    protected $stub;


    /**
     * A new stub for each test
     */
    public function setUp() {
        $this->stub = new OMP_Parser_Generic_List();
    }


    /**
     * Test the rawText getters and setters
     */
    public function test_rawText_getter_and_setter() {
        $rawText = <<<TEXT
This is a little test text over
        multiple lines, and with horrible indents
TEXT;

        $this->stub->setRawText($rawText);
        $this->assertEquals($rawText,
                            $this->stub->getRawText());
    }


    /**
     * Test the itemSpecifier getters and setters
     */
    public function test_itemSpecifier_getter_and_setter() {
        $itemSpecifier = '-';

        $this->stub->setItemSpecifier($itemSpecifier);
        $this->assertEquals($itemSpecifier,
                            $this->stub->getItemSpecifier());
    }

    /**
     * Test the constructor setters
     */
    public function test_constructor() {
        $throwaway = new OMP_Parser_Generic_List('Some text', '-');

        $this->assertNotNull($throwaway->getRawText(),
                             'Constructor did not set rawText correctly');
        $this->assertNotNull($throwaway->getItemSpecifier(),
                             'Constructor did not set itemSpecifier correctly');
    }



    /**
     * Test the overall functionality of the parser. Provide multiple valid
     * rawText examples, and ensure the output is correct.
     *
     * @dataProvider dataProvider_parse_valid
     */
    public function test_parse($rawText, $itemIdentifier, $expected, $match) {
        $this->stub->setRawText($rawText);
        $this->stub->setItemSpecifier($itemIdentifier);

        $this->assertEquals($expected, $this->stub->parse());
    }
    public function dataProvider_parse_valid() {
        $returnArray = array();
        $t = array();

        //First test involving short item list, 3 nesting levels
        $t[0] = <<<LIST
- Item 1
- Item 2
    - Subitem 1
    - Subitem 2
    - Subitem 3
        - Subsubitem 1
        - Subsubitem 2
- Item 4
    - Subitem 1
LIST;
        $t[1] = '-';
        $t[2] = array(
            array(
                'item'      => 'Item 1',
                'subitems'  => null
            ),
            array(
                'item'      => 'Item 2',
                'subitems'  => array(
                    array(
                        'item'      => 'Subitem 1',
                        'subitems'  => null
                    ),
                    array(
                        'item'      => 'Subitem 2',
                        'subitems'  => null
                    ),
                    array(
                        'item'      => 'Subitem 3',
                        'subitems'  => array(
                            array(
                                'item'      => 'Subsubitem 1',
                                'subitems'  => null
                            ),
                            array(
                                'item'      => 'Subsubitem 2',
                                'subitems'  => null
                            )
                        )
                    )
                )
            ),
            array(
                'item'      => 'Item 4',
                'subitems'  => array(
                    array(
                        'item'      => 'Subitem 1',
                        'subitems'  => null
                    )
                )
            )
        );
        $t[3] = true;
        $returnArray[] = $t;


        //Make sure things can cope with multi line items, and inline dashes
        $t[0] = <<<LIST
- Item 1
    - This subitem needs to span multiple lines, with linebreaks inbetween
      that have been put in manually. Also make sure that inline - dashes
      don't cause a fuss!'
    - Subitem 2
- Another item thats spanning over
multiple lines, but without proper alignment
    - Subitem 1
LIST;
        $t[1] = '-';
        $t[2] = array(
            array(
                'item'      => 'Item 1',
                'subitems'  => array(
                    array(
                        'item'      => "This subitem needs to span multiple lines, with linebreaks inbetween that have been put in manually. Also make sure that inline - dashes don't cause a fuss!'",
                        'subitems'  => null
                    ),
                    array(
                        'item'      => 'Subitem 2',
                        'subitems'  => null
                    )
                )
            ),
            array(
                'item'      => 'Another item thats spanning over multiple lines, but without proper alignment',
                'subitems'  => array(
                    array(
                        'item'      => 'Subitem 1',
                        'subitems'  => null
                    )
                )
            )
        );
        $t[3] = true;
        $returnArray[] = $t;
        return $returnArray;
    }


    /**
     * Test the parseLine function with valid data.
     *
     * @dataProvider dataProvider_parseLine_valid
     */
    public function test_parseLine_with_valid($rawLine,
                                              $itemSpecifier,
                                              $expectedLineType,
                                              $expectedSpecifierIdentLevel,
                                              $expectedContent,
                                              $shouldMatch = true) {

        $result = $this->stub->parseLine($rawLine, $itemSpecifier);
        $expected = array(
            'lineType'             => $expectedLineType,
            'specifierIndentLevel' => $expectedSpecifierIdentLevel,
            'content'              => $expectedContent
        );

        if($shouldMatch) {
            $this->assertEquals($expected,
                                $this->stub->parseLine($rawLine, $itemSpecifier));
        }
        else{
            $this->assertEquals($expected,
                                $this->stub->parseLine($rawLine, $itemSpecifier));
        }

    }
    public function dataProvider_parseLine_valid() {
        return array(
            array(
                '- Test new item with no indent',
                '-',
                'new',
                0,
                'Test new item with no indent',
                true
            ),
            array(
                ' - Test new item with 1 indent',
                '-',
                'new',
                1,
                'Test new item with 1 indent',
                true
            ),
            array(
                '    - Test new item with 4 indent',
                '-',
                'new',
                4,
                'Test new item with 4 indent',
                true
            ),
            array(
                'Non indented continuation line.',
                '-',
                'continuation',
                null,
                'Non indented continuation line.',
                true
            ),
            array(
                '         Indented continuation line.',
                '-',
                'continuation',
                null,
                'Indented continuation line.',
                true
            ),
            array(
                '  Indented - continuation line with inline specifier.',
                '-',
                'continuation',
                null,
                'Indented - continuation line with inline specifier.',
                true
            ),
            array(
                ' - Indented new item: with inline specifier - and punctuation',
                '-',
                'new',
                1,
                'Indented new item: with inline specifier - and punctuation',
                true
            )
        );

    }


    /**
     * Test the parseLine function with invalid data.
     *
     * @dataProvider dataProvider_parseLine_invalid
     */
    public function test_parseLine_with_invalid($rawLine, $itemSpecifier) {
        $this->setExpectedException('InvalidArgumentException');
        $result = $this->stub->parseLine($rawLine, $itemSpecifier);
    }
    public function dataProvider_parseLine_invalid() {
        return array(
            //Two empty strings
            array('', ''),

            //rawText only with whitespace
            array('    ', '-'),
            array('   ', ''),

            //rawText with specifier and only whitespace
            array('   -     ', '-')
        );
    }
}
