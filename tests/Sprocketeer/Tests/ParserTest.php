<?php

namespace Sprocketeer\Tests;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new \Sprocketeer\Parser(array(
            'js'       => realpath(__DIR__ . '/../../assets/js'),
            'provider' => realpath(__DIR__ . '/../../assets/provider')
        ));
    }

    public function testRequire()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => '02.js.coffee',
                    'canonical_path'   => 'js/02.js.coffee'
                ),
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/01.js.coffee'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => '01.js.coffee',
                    'canonical_path'   => 'js/01.js.coffee'
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/01.js.coffee'),
            'message'
        );
    }

    public function testRequireSelf()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/03.js.coffee'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => '03.js.coffee',
                    'canonical_path'   => 'js/03.js.coffee'
                ),
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => '02.js.coffee',
                    'canonical_path'   => 'js/02.js.coffee'
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/03.js.coffee'),
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
            $this->parser->getJsWebPaths('js/04.js.coffee', '/assets'),
            'message'
        );
    }

    public function testNonDirectiveComment()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/sub/01.js.coffee'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => 'sub/01.js.coffee',
                    'canonical_path'   => 'js/sub/01.js.coffee'
                ),
                array(
                    'absolute_path'    => realpath(__DIR__ . '/../../assets/js/05.js'),
                    'search_path_name' => 'js',
                    'search_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'  => '05.js',
                    'canonical_path'   => 'js/05.js'
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/05.js'),
            'message'
        );
    }
}
