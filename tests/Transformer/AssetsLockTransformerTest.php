<?php

namespace Alav\ComposerAssets\Tests\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Transformer\AssetsLockTransformer;
use Phake;

/**
 * Class AssetsLockTransformerTest
 */
class AssetsLockTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AssetsLockTransformer */
    protected $transformer;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->transformer = new AssetsLockTransformer();
    }

    /**
     * Test transform
     */
    public function testTransform()
    {
        $assetsNpm = array(
            "fakeAssetsNpm" => "fakeVersion"
        );
        $npmPackage = Phake::mock('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface');
        Phake::when($npmPackage)->getAssets()->thenReturn($assetsNpm);

        $assetsBower = array(
            "fakeAssetsBower" => "fakeVersion"
        );
        $bowerPackage = Phake::mock('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface');
        Phake::when($bowerPackage)->getAssets()->thenReturn($assetsBower);

        $transformPackage = $this->transformer->transform($npmPackage, $bowerPackage);

        $this->assertSame(AssetPackagesInterface::NAME_ASSETS, $transformPackage['name']);
        $this->assertSame(AssetsLockTransformer::DESCRIPTION, $transformPackage['description']);
        $this->assertSame($transformPackage[AssetPackagesInterface::NPM_TYPE], $assetsNpm);
        $this->assertSame($transformPackage[AssetPackagesInterface::BOWER_TYPE], $assetsBower);
    }


    /**
     * Test reverse transform
     */
    public function testReverseTransform()
    {
        $npm = array("fakeAssetsNpm" => "fakeVersion");
        $bower = array("fakeAssetsBower" => "fakeVersion");
        $jsonContent = array(
            AssetPackagesInterface::NPM_TYPE   => $npm,
            AssetPackagesInterface::BOWER_TYPE => $bower,
        );

        $transformPackage = $this->transformer->reverseTransform($jsonContent);
        $this->assertTrue(is_array($transformPackage));
        $this->assertCount(2, $transformPackage);
        list($assetsNpm, $assetsBower) = $transformPackage;

        $this->assertInstanceOf('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface', $assetsNpm);
        $this->assertInstanceOf('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface', $assetsBower);

        $this->assertSame($assetsNpm->getAssets(), $npm);
        $this->assertSame($assetsBower->getAssets(), $bower);
    }
}
