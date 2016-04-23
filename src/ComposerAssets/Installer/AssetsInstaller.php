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
    protected $io;

    public function __construct($vendorDir, PackageLoader $packageLoader, IOInterface $io)
    {
        $this->vendorDir = $vendorDir;
        $this->packageLoader = $packageLoader;
        $this->io = $io;
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

    /**
     * Install Npm Dependecies
     */
    public function installNpmDependencies()
    {
        $assetsNpm = $this->packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);

        $npmTransformer = new NpmTransformer();
        $assets = $npmTransformer->transform($assetsNpm);

        $jsonFileNpm = new NpmJsonFile();
        $jsonFileNpm->createPackageJson($assets);

     /*   $processExec = new ProcessExecutor($this->io);
        $cmd = ProcessExecutor::escape("npm install");
        $processExec->execute($cmd);*/
    }
}
