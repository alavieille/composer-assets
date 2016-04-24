<?php

namespace Alav\ComposerAssets\Installer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\BowerJsonFile;
use Alav\ComposerAssets\JsonFile\NpmJsonFile;
use Alav\ComposerAssets\Loader\PackageLoader;
use Alav\ComposerAssets\Transformer\BowerTransformer;
use Alav\ComposerAssets\Transformer\NpmTransformer;
use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;

/**
 * Class AssetsInstaller
 */
class AssetsInstaller
{

    protected $packageLoader;
    protected $vendorDir;
    protected $binDir;
    protected $io;

    /**
     * @param string        $vendorDir
     * @param string        $binDir
     * @param PackageLoader $packageLoader
     * @param IOInterface   $io
     */
    public function __construct(
        $vendorDir,
        $binDir,
        PackageLoader $packageLoader,
        IOInterface $io
    ) {
        $this->vendorDir = $vendorDir;
        $this->binDir = $binDir;
        $this->packageLoader = $packageLoader;
        $this->io = $io;
    }

    /**
     * Install Npm Dependencies
     */
    public function installNpmDependencies()
    {
        $assetsNpm = $this->packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);
        // install local bower
        if (false === $assetsNpm->hasAsset('bower')) {
            $assetsNpm->addAsset('bower', '*');
        }

        $npmTransformer = new NpmTransformer();
        $assets = $npmTransformer->transform($assetsNpm);

        $jsonFileNpm = new NpmJsonFile();
        $jsonFileNpm->createPackageJson($assets);

        $processExec = new ProcessExecutor($this->io);
        $npmBin = $this->binDir.'/npm';

        $processExec->execute($npmBin.' install');
    }

    /**
     * Install bower dependencies
     */
    public function installBowerDependencies()
    {
        $assetsBower = $this->packageLoader->extractAssets(AssetPackagesInterface::BOWER_TYPE);

        $bowerTransformer = new BowerTransformer();
        $assets = $bowerTransformer->transform($assetsBower);

        $jsonFileBower = new BowerJsonFile($this->vendorDir);
        $jsonFileBower->createBowerJson($assets);
    }
}
