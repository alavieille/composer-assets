<?php

namespace Alav\ComposerAssets\Tests;

use Composer\Script\ScriptEvents;
use Alav\ComposerAssets\ComposerAssetsPlugin;

/**
 * Class ComposerAssetsPluginTest
 */
class ComposerAssetsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerAssetsPlugin
     */
    protected $plugin;

    /**
     * Set Up
     */
    protected function setUp()
    {
        $this->plugin = new ComposerAssetsPlugin();
    }

    /**
     * Test Subscribed events
     */
    public function testGetSubscribedEvents()
    {
        $expected = array(
            ScriptEvents::POST_INSTALL_CMD => array(
                array('onPostInstall', 0)
            ),
            ScriptEvents::POST_UPDATE_CMD => array(
                array('onPostUpdate', 0)
            ),
        );
        $subscribedEvent = $this->plugin->getSubscribedEvents();
        $this->assertCount(2, $subscribedEvent);
        $this->assertSame($expected, $subscribedEvent);
    }
}
