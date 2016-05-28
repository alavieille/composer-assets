<?php

namespace Alav\ComposerAssets\Installer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\BowerJsonFile;
use Alav\ComposerAssets\JsonFile\NpmJsonFile;
use Alav\ComposerAssets\Loader\PackageLoader;
use Alav\ComposerAssets\Transformer\BowerTransformer;
use Alav\ComposerAssets\Transformer\NpmTransformer;
use Composer\Util\ProcessExecutor;

/**
 * Class AssetsInstaller
 */
class AssetsInstaller
{
    protected $packageLoader;
    protected $vendorDir;
    protected $binDir;
    protected $processExecutor;

    /**
     * @param string          $vendorDir
     * @param string          $binDir
     * @param PackageLoader   $packageLoader
     * @param ProcessExecutor $processExecutor
     */
    public function __construct(
        $vendorDir,
        $binDir,
        PackageLoader $packageLoader,
        ProcessExecutor $processExecutor
    ) {
        $this->vendorDir = $vendorDir;
        $this->binDir = $binDir;
        $this->packageLoader = $packageLoader;
        $this->processExecutor = $processExecutor;
    }

    /**
     * Install Npm Dependencies
     */
    public function installNpmDependencies()
    {
        $assetsNpm = $this->packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);
        // install local bower
        if ( false === $assetsNpm->hasAsset('bower') &&
             false === $this->hasGlobalBower()
        ) {
            $assetsNpm->addAsset('bower', '*');
        }

        $npmTransformer = new NpmTransformer();
        $assets = $npmTransformer->transform($assetsNpm);

        $jsonFileNpm = new NpmJsonFile();
        $jsonFileNpm->createPackageJson($assets);

        $npmBin = $this->binDir.'/npm';

        $this->processExecutor->execute($npmBin.' install');
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

        $bowerBin = $this->getBinBower();
        $this->processExecutor->execute($bowerBin.' install');
    }

    /**
     * Check if bower is installed globally
     */
    protected function hasGlobalBower()
    {
        $output = null;
        $this->processExecutor->execute('bower --version', $output);

        return null === $this->processExecutor->getErrorOutput();
    }

    /**
     * @return string
     *
     * @throws UnexistingBowerException
     */
    protected function getBinBower()
    {
        if ($this->hasGlobalBower()) {
            $bin = 'bower';
        } elseif (file_exists('node_modules/.bin/bower')) {
            $bin = 'node_modules/.bin/bower';
        } else {
            throw new UnexistingBowerException;
        }

        return $bin;
    }
}
