<?php

namespace Alav\ComposerAssets\Tests\JsonFile;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\AssetsLockFile;

/**
 * Class AssetsLockFileTest
 */
class AssetsLockFileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AssetsLockFile
     */
    protected $assetsLockFile;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->assetsLockFile = new AssetsLockFile();
    }

    /**
     * Tear Down
     */
    protected function tearDown()
    {
        $file = 'assets.lock';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * test create assets lock json
     */
    public function testCreateAssetsLockFile()
    {
        $content = array(
            "name" => AssetPackagesInterface::NAME_ASSETS
        );
        $this->assetsLockFile->createAssetsLockFile($content);
        $this->assertFileExists('assets.lock');
        $this->assertJsonStringEqualsJsonFile('assets.lock', json_encode($content));
    }

    /**
     * test create package json
     */
    public function testCreateAssetsLockFileWithException()
    {
        $content = array(
            "name" => "fakeName"
        );
        $this->assetsLockFile->createAssetsLockFile($content);
        $this->setExpectedException('Alav\ComposerAssets\JsonFile\JsonFileException');
        $this->assetsLockFile->createAssetsLockFile($content);
    }
}
