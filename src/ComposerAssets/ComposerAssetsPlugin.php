<?php

namespace Alav\ComposerAssets;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Installer\AssetsInstaller;
use Alav\ComposerAssets\JsonFile\AssetsLockFile;
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
     * @param Event $event
     */
    public function onPostInstall(Event $event)
    {
        $assetLockFile = new AssetsLockFile();

        if (false === $assetLockFile->existFile()) {
            $this->onPostUpdate($event);
        }

        $assetLockTransformer = new AssetsLockTransformer();

        $jsonContent = $assetLockFile->readJsonFile();
        list($assetsNpm, $assetsBower) = $assetLockTransformer->reverseTransform($jsonContent);

        $installer = $this->getInstallerAssets();
        $installer->installNpmDependencies($assetsNpm);
        $installer->installBowerDependencies($assetsBower);
    }

    /**
     * @param Event $event
     */
    public function onPostUpdate(Event $event)
    {
        $packages = $this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
        $package = $this->composer->getPackage();
        $packageLoader = new PackageLoader($package, $packages);

        $assetsNpm = $packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);
        $assetsBower = $packageLoader->extractAssets(AssetPackagesInterface::BOWER_TYPE);

        $installer = $this->getInstallerAssets();
        $installer->installNpmDependencies($assetsNpm);
        $installer->installBowerDependencies($assetsBower);

        $assetLockTransformer = new AssetsLockTransformer();
        $assets = $assetLockTransformer->transform($assetsNpm, $assetsBower);
        $assetLockFile = new AssetsLockFile();
        $assetLockFile->createAssetsLockFile($assets);
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
}
