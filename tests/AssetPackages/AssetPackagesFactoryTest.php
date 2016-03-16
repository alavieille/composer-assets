<?php

namespace Alav\ComposerAssets\Tests\AssetPackages;

use Alav\ComposerAssets\AssetPackages\AssetPackagesFactory;

/**
 * Class AssetPackagesFactoryTest
 */
class AssetPackagesFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $assetType
     * @param string $class
     *
     * @dataProvider dataProviderAssetTypeAndClass
     */
    public function testCreateAssetPackages($assetType, $class)
    {
        $assetPackage = AssetPackagesFactory::createAssetPackages($assetType);
        $this->assertInstanceOf($class, $assetPackage);
    }

    /**
     * @return array
     */
    public function dataProviderAssetTypeAndClass()
    {
        return array(
            "npm type" => array("npm", 'Alav\ComposerAssets\AssetPackages\NpmAssetPackages'),
            "bower type" => array("bower", 'Alav\ComposerAssets\AssetPackages\BowerAssetPackages'),
        );
    }
}
