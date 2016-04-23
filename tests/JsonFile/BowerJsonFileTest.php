<?php

namespace Alav\ComposerAssets\Tests\JsonFile;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\BowerJsonFile;

/**
 * Class BowerJsonFileTest
 */
class BowerJsonFileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BowerJsonFile
     */
    protected $bowerJsonFile;
    protected $fakeVendorDir = 'fake-vendor-dir';

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->bowerJsonFile = new BowerJsonFile($this->fakeVendorDir);
    }

    /**
     * Tear Down
     */
    protected function tearDown()
    {
        $file = 'bower.json';
        if (file_exists($file)) {
            unlink($file);
        }
        $file = '.bowerrc';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * test create package json
     */
    public function testCreatePackageJson()
    {
        $content = array(
            "name" => AssetPackagesInterface::NAME_ASSETS
        );
        $bowerContent = array(
            "directory" => $this->fakeVendorDir.'/bower_components'
        );
        $this->bowerJsonFile->createBowerJson($content);
        $this->assertFileExists('bower.json');
        $this->assertFileExists('.bowerrc');
        $this->assertJsonStringEqualsJsonFile('bower.json', json_encode($content));
        $this->assertJsonStringEqualsJsonFile('.bowerrc', json_encode($bowerContent));
    }

    /**
     * test create package json
     */
    public function testCreatePackageJsonWithException()
    {
        $content = array(
            "name" => "fakeName"
        );
        $this->bowerJsonFile->createBowerJson($content);
        $this->setExpectedException('Alav\ComposerAssets\JsonFile\JsonFileException');
        $this->bowerJsonFile->createBowerJson($content);
    }
}
