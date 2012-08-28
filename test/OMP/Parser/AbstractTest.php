<?php

class OMP_Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('OMP_Parser_Abstract');
    }


    public function test_rawText_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setRawText($exampleText);
        $this->assertEquals($this->stub->getRawText(), $exampleText);
    }

    public function test_parsedData_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setParsedData($exampleText);
        $this->assertEquals($this->stub->getParsedData(), $exampleText);
    }

    public function test_postConsumedText_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setPostConsumedText($exampleText);
        $this->assertEquals($this->stub->getPostConsumedText(), $exampleText);
    }

    public function test_activeComponents_getters_setters() {
        $exampleComponents = array('Ingredients', 'Method', 'Tips');
        $this->stub->setActiveComponents($exampleComponents);
        $this->assertEquals($this->stub->getActiveComponents(),
                            $exampleComponents);
    }

    public function test_getParsedDataAsJson_() {
        $this->stub->setParsedData(
            array(
                'Ingredients' => array(
                    '_' => array(
                        array(
                            'name'      => 'Test Ingredient',
                            'quantity'  => '2 tsp',
                            'directive' => null
                        ),
                        array(
                            'name'      => 'Another',
                            'quantity'  => '10 g (1 tsp)',
                            'directive' => 'Sifted, gently'
                        ),
                        array(
                            'name'      => 'Clove',
                            'quantity'  => null,
                            'directive' => null
                        )
                    )
                )
            )
        );

        /* FORMATTED JSON
        {
            "Ingredients" : {
                "_" : [
                    {
                        "name":"Test Ingredient",
                        "quantity":"2 tsp",
                        "directive":null
                    },
                    {
                        "name":"Another",
                        "quantity":"10 g (1 tsp)",
                        "directive":"Sifted, gently"
                    },
                    {
                        "name":"Clove",
                        "quantity":null,
                        "directive":null
                    }
                ]
            }
        }
        */
        $expectedJson = '{"Ingredients":{"_":[{"name":"Test Ingredient","quantity":"2 tsp","directive":null},{"name":"Another","quantity":"10 g (1 tsp)","directive":"Sifted, gently"},{"name":"Clove","quantity":null,"directive":null}]}}';

        $this->assertEquals($expectedJson, $this->stub->getParsedDataAsJson());
    }
}
