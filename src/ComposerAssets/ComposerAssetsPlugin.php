<?php

namespace Alav\ComposerAssets;

use Alav\ComposerAssets\Installer\AssetsInstaller;
use Alav\ComposerAssets\Loader\PackageLoader;
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
        $packages = $this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
        $package = $this->composer->getPackage();

        $packageLoader = new PackageLoader($package, $packages);
        $vendorDir = $this->composer->getConfig()->get('vendor-dir');
        $binDir = $this->composer->getConfig()->get('bin-dir');

        $processExecutor = new ProcessExecutor($this->io);
        $installer = new AssetsInstaller($vendorDir, $binDir, $packageLoader, $processExecutor);
        $installer->installNpmDependencies();
        $installer->installBowerDependencies();
    }

    /**
     * @param Event $event
     */
    public function onPostUpdate(Event $event)
    {
        $this->composer->getRepositoryManager()->getLocalRepository()->getCanonicalPackages();
        $this->composer->getPackage();
    }
}
