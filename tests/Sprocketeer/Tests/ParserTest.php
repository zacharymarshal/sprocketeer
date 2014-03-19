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
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '02.js.coffee',
                    'sprocketeer_path'   => 'js/02.js.coffee',
                    'last_modified'      => 1391749909,
                ),
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/01.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '01.js.coffee',
                    'sprocketeer_path'   => 'js/01.js.coffee',
                    'last_modified'      => 1394550332
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/01.js.coffee'),
            'message'
        );
    }

    public function testRequireFromSpecificSearchPath()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '02.js.coffee',
                    'sprocketeer_path'   => 'js/02.js.coffee',
                    'last_modified'      => 1391749909,
                ),
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/provider/testing/01.js.coffee'),
                    'category_path_name' => 'provider',
                    'category_path'      => realpath(__DIR__ . '/../../assets/provider'),
                    'requested_asset'    => 'testing/01.js.coffee',
                    'sprocketeer_path'   => 'provider/testing/01.js.coffee',
                    'last_modified'      => 1392271181,
                ),
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/07.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '07.js.coffee',
                    'sprocketeer_path'   => 'js/07.js.coffee',
                    'last_modified'      => 1394550332,
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/07.js.coffee'),
            'message'
        );
    }

    public function testRequireWithoutReadingManifest()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/01.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '01.js.coffee',
                    'sprocketeer_path'   => 'js/01.js.coffee',
                    'last_modified'      => 1394550332,
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/01.js.coffee', false),
            'message'
        );
    }

    public function testRequireSelf()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/03.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '03.js.coffee',
                    'sprocketeer_path'   => 'js/03.js.coffee',
                    'last_modified'      => 1394550332,
                ),
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/02.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '02.js.coffee',
                    'sprocketeer_path'   => 'js/02.js.coffee',
                    'last_modified'      => 1391749909,
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/03.js.coffee'),
            'message'
        );
    }

    public function testNonDirectiveComment()
    {
        $this->assertEquals(
            array(
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/sub/01.js.coffee'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => 'sub/01.js.coffee',
                    'sprocketeer_path'   => 'js/sub/01.js.coffee',
                    'last_modified'      => 1392008453,
                ),
                array(
                    'absolute_path'      => realpath(__DIR__ . '/../../assets/js/05.js'),
                    'category_path_name' => 'js',
                    'category_path'      => realpath(__DIR__ . '/../../assets/js'),
                    'requested_asset'    => '05.js',
                    'sprocketeer_path'   => 'js/05.js',
                    'last_modified'      => 1394550332,
                ),
            ),
            $this->parser->getPathInfoFromManifest('js/05.js'),
            'message'
        );
    }
}
