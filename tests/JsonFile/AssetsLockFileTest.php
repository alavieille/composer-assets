<?php

namespace Alav\ComposerAssets\Tests\JsonFile;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\AssetsLockFile;
use Composer\Json\JsonFile;

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
        $fileFake = 'fake.lock';
        if (file_exists($fileFake)) {
            unlink($fileFake);
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

    /**
     * test read json file
     */
    public function testReadJsonFile()
    {
        $jsonFile = new JsonFile('assets.lock');
        $content = array(
            'name' => AssetPackagesInterface::NAME_ASSETS,
            'content' => 'fakeContent',
        );
        $jsonFile->write($content);

        $jsonFile = $this->assetsLockFile->readJsonFile();

        $this->assertSame($content, $jsonFile);
    }

    /**
     * @param string $name
     * @param bool   $fileExist
     *
     * @dataProvider fileNameExist
     */
    public function testExistFile($name, $fileExist)
    {
        $jsonFile = new JsonFile($name);
        $jsonFile->write(array());
        $this->assertSame($fileExist, $this->assetsLockFile->existFile());
    }

    /**
     * @return array
     */
    public function fileNameExist()
    {
        return array(
            array("assets.lock", true),
            array("fake.lock", false),
        );
    }
}
