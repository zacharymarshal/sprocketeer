<?php

namespace Sprocketeer\Tests;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new \Sprocketeer\Parser(array(
            realpath(__DIR__ . '/../../assets/js'),
            realpath(__DIR__ . '/../../assets/provider')
        ));
    }

    public function testRequire()
    {
        $this->assertEquals(
            array(
                realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                realpath(__DIR__ . '/../../assets/js/01.js.coffee'),
            ),
            $this->parser->getJsFiles('01'),
            'message'
        );
    }

    public function testRequireSelf()
    {
        $this->assertEquals(
            array(
                realpath(__DIR__ . '/../../assets/js/03.js.coffee'),
                realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
            ),
            $this->parser->getJsFiles('03'),
            'message'
        );
    }

    public function testJsWebPaths()
    {
        $this->assertEquals(
            array(
                '/assets/sub/01.js.coffee',
                '/assets/04.js.coffee',
            ),
            $this->parser->getJsWebPaths('04', '/assets'),
            'message'
        );
    }

    public function testNonDirectiveComment()
    {
        $this->assertEquals(
            array(
                realpath(__DIR__ . '/../../assets/js/sub/01.js.coffee'),
                realpath(__DIR__ . '/../../assets/js/05.js'),
            ),
            $this->parser->getJsFiles('05'),
            'message'
        );
    }
}
