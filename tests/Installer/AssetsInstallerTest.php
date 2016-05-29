<?php

namespace Alav\ComposerAssets\Tests\Installer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Installer\AssetsInstaller;
use Phake;

/**
 * Class AssetsInstallerTest
 */
class AssetsInstallerTest extends \PHPUnit_Framework_TestCase
{

    protected $vendorDir = 'vendor';
    protected $binDir = 'bin';
    protected $packageLoader;
    protected $processExecutor;
    /**
     * @var AssetsInstaller
     */
    protected $assetInstaller;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->packageLoader = Phake::mock('Alav\ComposerAssets\Loader\PackageLoader');
        $this->processExecutor = Phake::mock('Composer\Util\ProcessExecutor');

        $this->assetInstaller = new AssetsInstaller(
            $this->vendorDir,
            $this->binDir,
            $this->processExecutor
        );
    }

    /**
     * Test install npm dependencies
     */
    public function testInstallNpmDependencies()
    {
        $assetPackages = Phake::mock('Alav\ComposerAssets\AssetPackages\AbstractAssetPackages');
        Phake::when($assetPackages)->hasAsset('bower')->thenReturn(false);
        Phake::when($this->processExecutor)->getErrorOutput()->thenReturn('no global');
        Phake::when($this->packageLoader)->extractAssets(AssetPackagesInterface::NPM_TYPE)->thenReturn($assetPackages);

        $this->assetInstaller->installNpmDependencies($assetPackages);

        Phake::verify($this->processExecutor)->execute($this->binDir.'/npm install');
    }

    /**
     * Test install bower dependencies with exception
     */
    public function testInstallBowerDependenciesException()
    {
        $assetPackages = Phake::mock('Alav\ComposerAssets\AssetPackages\AbstractAssetPackages');
        Phake::when($assetPackages)->hasAsset('bower')->thenReturn(false);
        Phake::when($this->processExecutor)->getErrorOutput()->thenReturn('no global');
        Phake::when($this->packageLoader)->extractAssets(AssetPackagesInterface::BOWER_TYPE)->thenReturn($assetPackages);
        $this->setExpectedException('Alav\ComposerAssets\Installer\UnexistingBowerException');

        $this->assetInstaller->installBowerDependencies($assetPackages);
    }

    /**
     * Test install bower dependencies
     */
    public function testInstallBowerDependencies()
    {
        $assetPackages = Phake::mock('Alav\ComposerAssets\AssetPackages\AbstractAssetPackages');
        Phake::when($assetPackages)->hasAsset('bower')->thenReturn(true);
        Phake::when($this->processExecutor)->getErrorOutput()->thenReturn(null);
        Phake::when($this->packageLoader)->extractAssets(AssetPackagesInterface::BOWER_TYPE)->thenReturn($assetPackages);

        $this->assetInstaller->installBowerDependencies($assetPackages);
        Phake::verify($this->processExecutor)->execute('bower install');

    }
}
