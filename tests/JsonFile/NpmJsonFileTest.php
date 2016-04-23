<?php

namespace Alav\ComposerAssets\Tests\JsonFile;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\NpmJsonFile;

/**
 * Class NpmJsonFileTest
 */
class NpmJsonFileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var NpmJsonFile
     */
    protected $npmJsonFile;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->npmJsonFile = new NpmJsonFile();
    }

    /**
     * Tear Down
     */
    protected function tearDown()
    {
        $file = 'package.json';
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
        $this->npmJsonFile->createPackageJson($content);
        $this->assertFileExists('package.json');
        $this->assertJsonStringEqualsJsonFile('package.json', json_encode($content));
    }

    /**
     * test create package json
     */
    public function testCreatePackageJsonWithException()
    {
        $content = array(
            "name" => "fakeName"
        );
        $this->npmJsonFile->createPackageJson($content);
        $this->setExpectedException('Alav\ComposerAssets\JsonFile\JsonFileException');
        $this->npmJsonFile->createPackageJson($content);
    }
}
