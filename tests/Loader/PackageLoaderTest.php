<?php

namespace Alav\ComposerAssets\Tests\Loader;

use Alav\ComposerAssets\Loader\PackageLoader;
use Phake;

/**
 * Class PackageLoaderTest
 */
class PackageLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $rootPackage;
    protected $vendor1;
    protected $vendor2;
    protected $packageLoader;

    protected $vendorPackage;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->rootPackage = Phake::mock('Composer\Package\RootPackageInterface');
        $this->vendor1 = Phake::mock('Composer\Package\PackageInterface');
        Phake::when($this->vendor1)->getExtra()->thenReturn(array());
        $this->vendor2 = Phake::mock('Composer\Package\PackageInterface');
        Phake::when($this->vendor2)->getExtra()->thenReturn(array());
        $this->vendorPackage = array($this->vendor1, $this->vendor2);

        $this->packageLoader = new PackageLoader($this->rootPackage, $this->vendorPackage);
    }

    /**
     * @param array $extra
     * @param array $extraVendor1
     * @param array $extraVendor2
     * @param array $expectedAssets
     *
     * @dataProvider dataProviderNpmExtraVendorAndExpectedAssets
     */
    public function testNpmExtractAssets($extra, $extraVendor1, $extraVendor2, $expectedAssets)
    {
        $assetType = 'npm';
        Phake::when($this->rootPackage)->getExtra()->thenReturn($extra);
        Phake::when($this->vendor1)->getExtra()->thenReturn($extraVendor1);
        Phake::when($this->vendor2)->getExtra()->thenReturn($extraVendor2);
        $assetPackage = $this->packageLoader->extractAssets($assetType);

        $this->assertInstanceOf('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface', $assetPackage);
        $assets = $assetPackage->getAssets();
        $this->assertEquals($expectedAssets, $assets);
    }

    /**
     * @return array
     */
    public function dataProviderNpmExtraVendorAndExpectedAssets()
    {
        return array(
            'empty' => array(
                array(),
                array(),
                array(),
                array()
            ),
            'npm package' => array(
                array('npm-assets' => array('fakePackage' => '1.0')),
                array(),
                array(),
                array('fakePackage' => '1.0'),
            ),
            'npm package and other' => array(
                array('npm-assets' => array('fakePackage' => '1.0'), 'other-assets' => array('otherPackage' => '5.0')),
                array(),
                array(),
                array('fakePackage' => '1.0'),
            ),
            'npm package and bower' => array(
                array('npm-assets' => array('fakePackage' => '1.0'), 'bower-assets' => array('bowerPackage' => '5.0')),
                array(),
                array(),
                array('fakePackage' => '1.0'),
            ),
            'multiple npm package' => array(
                array('npm-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array(),
                array(),
                array('fakePackage' => '1.0', 'fakePackage2' => '*'),
            ),
            'npm package with vendor' => array(
                array('npm-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('npm-assets' => array('vendor1Package' => '~1.0')),
                array('npm-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor1Package' => '~1.0', 'vendor2Package' => '1.x'),
            ),
            'npm package with one vendor' => array(
                array('npm-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('npm-assets' => array('vendor1Package' => '~1.0')),
                array(),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor1Package' => '~1.0'),
            ),
            'npm package with same version' => array(
                array('npm-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('npm-assets' => array('fakePackage' => '~1.0')),
                array('npm-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0 ~1.0', 'fakePackage2' => '*', 'vendor2Package' => '1.x'),
            ),
            'npm package with vendor bower' => array(
                array('npm-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('npm-assets' => array('vendor1Package' => '~1.0')),
                array('bower-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor1Package' => '~1.0'),
            ),
        );
    }

    /**
     * @param array $extra
     * @param array $extraVendor1
     * @param array $extraVendor2
     * @param array $expectedAssets
     *
     * @dataProvider dataProviderBowerExtraVendorAndExpectedAssets
     */
    public function testBowerExtractAssets($extra, $extraVendor1, $extraVendor2, $expectedAssets)
    {
        $assetType = 'bower';
        Phake::when($this->rootPackage)->getExtra()->thenReturn($extra);
        Phake::when($this->vendor1)->getExtra()->thenReturn($extraVendor1);
        Phake::when($this->vendor2)->getExtra()->thenReturn($extraVendor2);
        $assetPackage = $this->packageLoader->extractAssets($assetType);

        $this->assertInstanceOf('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface', $assetPackage);
        $assets = $assetPackage->getAssets();
        $this->assertEquals($expectedAssets, $assets);
    }

    /**
     * @return array
     */
    public function dataProviderBowerExtraVendorAndExpectedAssets()
    {
        return array(
            'empty' => array(
                array(),
                array(),
                array(),
                array(),
            ),
            'bower package' => array(
                array('bower-assets' => array('fakePackage' => '1.0')),
                array(),
                array(),
                array('fakePackage' => '1.0'),
            ),
            'bower package and other' => array(
                array('bower-assets' => array('fakePackage' => '1.0'), 'other-assets' => array('otherPackage' => '5.0')),
                array(),
                array(),
                array('fakePackage' => '1.0'),
            ),
            'bower package and npm' => array(
                array('npm-assets' => array('fakePackage' => '1.0'), 'bower-assets' => array('bowerPackage' => '5.0')),
                array(),
                array(),
                array('bowerPackage' => '5.0'),
            ),
            'multiple bower package' => array(
                array('bower-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array(),
                array(),
                array('fakePackage' => '1.0', 'fakePackage2' => '*'),
            ),
            'bower package with vendor' => array(
                array('bower-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('bower-assets' => array('vendor1Package' => '~1.0')),
                array('bower-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor1Package' => '~1.0', 'vendor2Package' => '1.x'),
            ),
            'bower package with vendor npm' => array(
                array('bower-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('npm-assets' => array('vendor1Package' => '~1.0')),
                array('bower-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor2Package' => '1.x'),
            ),
            'bower package with one vendor' => array(
                array('bower-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('bower-assets' => array('vendor1Package' => '~1.0')),
                array(),
                array('fakePackage' => '1.0', 'fakePackage2' => '*', 'vendor1Package' => '~1.0'),
            ),
            'bower package with same version' => array(
                array('bower-assets' => array('fakePackage' => '1.0', 'fakePackage2' => '*')),
                array('bower-assets' => array('fakePackage' => '~1.0')),
                array('bower-assets' => array('vendor2Package' => '1.x')),
                array('fakePackage' => '1.0 ~1.0', 'fakePackage2' => '*', 'vendor2Package' => '1.x'),
            ),
        );
    }
}
