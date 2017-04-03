<?php

namespace Alav\ComposerAssets;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Installer\AssetsInstaller;
use Alav\ComposerAssets\JsonFile\AssetsLockFile;
use Alav\ComposerAssets\JsonFile\JsonFileException;
use Alav\ComposerAssets\Loader\PackageLoader;
use Alav\ComposerAssets\Transformer\AssetsLockTransformer;
use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\Util\ProcessExecutor;

/**
 * Class ComposerAssetsPlugin
 */
class ComposerAssetsPlugin implements PluginInterface, EventSubscriberInterface
{

    /**
     * @var Composer
     */
    protected $composer;
    /** @var  IOInterface */
    protected $io;

    /**
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::POST_INSTALL_CMD => array(
                array('onPostInstall', 0)
            ),
            ScriptEvents::POST_UPDATE_CMD => array(
                array('onPostUpdate', 0)
            ),
        );
    }

    /**
     * On post install
     */
    public function onPostInstall()
    {
        $assetLockFile = new AssetsLockFile();

        if (false === $assetLockFile->existFile()) {
            $this->onPostUpdate();
        }
        try {
            $assetLockTransformer = new AssetsLockTransformer();

            $this->io->write('<info>Reading assets lock file</info>', true);
            $jsonContent = $assetLockFile->readJsonFile();
            list($assetsNpm, $assetsBower) = $assetLockTransformer->reverseTransform($jsonContent);

            $installer = $this->getInstallerAssets();

            $this->io->write('<info>Installing Npm dependencies:</info>', true);
            $this->generateOutputAssets($assetsNpm);
            $installer->installNpmDependencies($assetsNpm);

            if (!empty($assetsBower->getAssets())) {
                $this->io->write('<info>Installing Bower dependencies:</info>', true);
                $this->generateOutputAssets($assetsBower);
                $installer->installBowerDependencies($assetsBower);
            }
        } catch (JsonFileException $e) {
            $this->io->writeError('<error>' . $e->getMessage() . '<error>');
        }
    }

    /**
     * On post update
     */
    public function onPostUpdate()
    {
        try {
            $packages = $this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
            $package = $this->composer->getPackage();
            $packageLoader = new PackageLoader($package, $packages);

            $assetsNpm = $packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);
            $assetsBower = $packageLoader->extractAssets(AssetPackagesInterface::BOWER_TYPE);
            $installer = $this->getInstallerAssets();

            $this->io->write('<info>Installing Npm dependencies:</info>', true);
            $this->generateOutputAssets($assetsNpm);

            $installer->installNpmDependencies($assetsNpm);

            if (!empty($assetsBower->getAssets())) {
                $this->io->write('<info>Installing Bower dependencies:</info>', true);
                $this->generateOutputAssets($assetsBower);
                $installer->installBowerDependencies($assetsBower);
            }

            $assetLockTransformer = new AssetsLockTransformer();
            $assets = $assetLockTransformer->transform($assetsNpm, $assetsBower);
            $assetLockFile = new AssetsLockFile();
            $this->io->write('<info>Writing assets lock file</info>', true);
            $assetLockFile->createAssetsLockFile($assets);
        } catch (JsonFileException $e) {
            $this->io->writeError('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * @return AssetsInstaller
     */
    protected function getInstallerAssets()
    {
        $vendorDir = $this->composer->getConfig()->get('vendor-dir');
        $binDir = $this->composer->getConfig()->get('bin-dir');

        $processExecutor = new ProcessExecutor($this->io);

        return new AssetsInstaller($vendorDir, $binDir, $processExecutor);
    }

    /**
     * @param AssetPackagesInterface $assetPackage
     */
    protected function generateOutputAssets(AssetPackagesInterface $assetPackage)
    {
        $assets = $assetPackage->getAssets();
        if (count($assets) < 1) {
            $this->io->write(' <info>no dependencies found</info>', true);
        }
        foreach ($assets as $name => $version) {
            $this->io->write(' - <info>'.$name.'</info> (<comment>'.$version.'</comment>)', true);
            $this->io->write('', true);
        }
    }
}
