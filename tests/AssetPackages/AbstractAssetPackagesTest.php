<?php

namespace Alav\ComposerAssets\Tests\AssetPackages;

use Alav\ComposerAssets\AssetPackages\AbstractAssetPackages;

/**
 * Class AbstractAssetPackagesTest
 */
abstract class AbstractAssetPackagesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractAssetPackages
     */
    protected $packages;

    /**
     * Test implement interface
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface', $this->packages);
    }

    /**
     * @param string $name
     * @param string $version
     *
     * @dataProvider dataProviderPackageNameAndVersion
     */
    public function testAddAsset($name, $version)
    {
        $this->packages->addAsset($name, $version);

        $assets = $this->packages->getAssets();
        $this->assertArrayHasKey($name, $assets);
        $this->assertEquals($version, $assets[$name]);
    }

    /**
     * @return array
     */
    public function dataProviderPackageNameAndVersion()
    {
        return array(
            "version *" => array('npm-package', "*"),
            "fixed version" => array('bower-package', "1.0"),
            "fake-package" => array('fake-package', "~5.0"),
        );
    }

    /**
     * Test add asset with same package name
     */
    public function testAddAssetWithSamePackageName()
    {
        $packageName = 'fakePackage';
        $firstVersion = '*';
        $secondVersion = '1.0';

        $this->packages->addAsset($packageName, $firstVersion);
        $assets = $this->packages->getAssets();
        $this->assertArrayHasKey($packageName, $assets);
        $this->assertEquals($firstVersion, $assets[$packageName]);

        $this->packages->addAsset($packageName, $secondVersion);
        $assets = $this->packages->getAssets();
        $this->assertArrayHasKey($packageName, $assets);
        $this->assertEquals($firstVersion.' '.$secondVersion, $assets[$packageName]);

    }
}
