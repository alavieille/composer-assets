<?php

namespace Alav\ComposerAssets;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Loader\PackageLoader;
use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

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
        $packageLoader->extractAssets(AssetPackagesInterface::NPM_TYPE);
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
