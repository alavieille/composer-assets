<?php

namespace Alav\ComposerAssets\Installer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\JsonFile\BowerJsonFile;
use Alav\ComposerAssets\JsonFile\NpmJsonFile;
use Alav\ComposerAssets\Transformer\BowerTransformer;
use Alav\ComposerAssets\Transformer\NpmTransformer;
use Composer\Util\ProcessExecutor;

/**
 * Class AssetsInstaller
 */
class AssetsInstaller
{
    protected $vendorDir;
    protected $binDir;
    protected $processExecutor;

    /**
     * @param string          $vendorDir
     * @param string          $binDir
     * @param ProcessExecutor $processExecutor
     */
    public function __construct(
        $vendorDir,
        $binDir,
        ProcessExecutor $processExecutor
    ) {
        $this->vendorDir = $vendorDir;
        $this->binDir = $binDir;
        $this->processExecutor = $processExecutor;
    }

    /**
     * Install Npm Dependencies
     *
     * @param AssetPackagesInterface $assetsNpm
     */
    public function installNpmDependencies(AssetPackagesInterface $assetsNpm)
    {
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
     *
     * @param AssetPackagesInterface $assetsBower
     */
    public function installBowerDependencies(AssetPackagesInterface $assetsBower)
    {
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
