<?php

namespace Alav\ComposerAssets\Functional\Tests;

use Alav\ComposerAssets\JsonFile\AssetsLockFile;
use Composer\Script\ScriptEvents;
use Alav\ComposerAssets\ComposerAssetsPlugin;
use Phake;

/**
 * Class ComposerAssetsPluginTest
 */
class ComposerAssetsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerAssetsPlugin
     */
    protected $plugin;
    protected $localRepository;
    protected $event;
    protected $rootPackage;
    protected $io;
    protected $vendorDir = 'fake-vendor';
    protected $nodeModuleDir = 'node_modules';
    protected $bowerrcFile = '.bowerrc';
    protected $bowerFile = 'bower.json';
    protected $packageFile = 'package.json';
    protected $lockFile = 'assets.lock';

    /**
     * Set Up
     */
    protected function setUp()
    {
        $this->plugin = new ComposerAssetsPlugin();

        $this->event = Phake::mock('Composer\EventDispatcher\Event');
        $composer = Phake::mock('Composer\Composer');
        $repositoryManager = Phake::mock('Composer\Repository\RepositoryManager');
        $this->localRepository = Phake::mock('Composer\Repository\WritableRepositoryInterface');
        $this->rootPackage = Phake::mock('Composer\Package\RootPackageInterface');
        $config = Phake::mock('Composer\Config');

        Phake::when($repositoryManager)->getLocalRepository()->thenReturn($this->localRepository);
        Phake::when($config)->get('bin-dir')->thenReturn('vendor/bin');
        Phake::when($config)->get('vendor-dir')->thenReturn($this->vendorDir);
        Phake::when($composer)->getRepositoryManager()->thenReturn($repositoryManager);
        Phake::when($composer)->getPackage()->thenReturn($this->rootPackage);
        Phake::when($composer)->getConfig()->thenReturn($config);

        $this->io = Phake::mock('Composer\IO\IOInterface');
        $this->plugin->activate($composer, $this->io);
    }

    /**
     * Tear Down
     */
    public function tearDown()
    {
        if (is_dir($this->nodeModuleDir)) {
            $this->delDir($this->nodeModuleDir);
        }
        if (is_dir($this->vendorDir)) {
            $this->delDir($this->vendorDir);
        }
        if (is_file($this->bowerFile)) {
            unlink($this->bowerFile);
        }
        if (is_file($this->packageFile)) {
            unlink($this->packageFile);
        }
        if (is_file($this->bowerrcFile)) {
            unlink($this->bowerrcFile);
        }
        if (is_file($this->lockFile)) {
            unlink($this->lockFile);
        }
    }

    /**
     * test post install
     */
    public function testOnPostInstall()
    {
        $lock = array(
            "name"=> "assets-packages",
            "description" => "This file is auto-generated. Do not change it",
            "npm" => array('which' => '*', 'test' => '~0.5', 'bower' => '*'),
            "bower" => array('moment' => '2.0', 'lodash' => '1.0'),
        );
        $assetLock = new AssetsLockFile();
        $assetLock->createAssetsLockFile($lock);

        $this->plugin->onPostInstall($this->event);

        $this->assertAssetsIsInstalled();
    }

    /**
     * test post install without lock
     */
    public function testOnPostInstallWithoutLock()
    {

        $extraVendorPackage1 = array(
            'npm-assets' => array(
                'which' => '*',
            ),
            'bower-assets' => array(
                'lodash' => '1.0'
            )
        );
        $extraVendorPackage2 = array(
            'npm-assets' => array(
                'test' => '~0.5',
            )
        );
        $extraRootPackage = array(
            'bower-assets' => array(
                'moment' => '2.0',
            )
        );
        $vendorPackage = $this->createMockPackage($extraVendorPackage1);
        $vendorPackage2 = $this->createMockPackage($extraVendorPackage2);
        $packages = array($vendorPackage, $vendorPackage2);
        Phake::when($this->localRepository)->getCanonicalPackages()->thenReturn($packages);
        Phake::when($this->rootPackage)->getExtra()->thenReturn($extraRootPackage);

        $this->plugin->onPostInstall($this->event);

        $this->assertAssetsIsInstalled();
    }

    /**
     * test post update
     */
    public function testOnPostUpdate()
    {
        $extraVendorPackage1 = array(
            'npm-assets' => array(
                'which' => '*',
            ),
            'bower-assets' => array(
                'lodash' => '1.0'
            )
        );
        $extraVendorPackage2 = array(
            'npm-assets' => array(
                'test' => '~0.5',
            )
        );
        $extraRootPackage = array(
            'bower-assets' => array(
                'moment' => '2.0',
            )
        );
        $vendorPackage = $this->createMockPackage($extraVendorPackage1);
        $vendorPackage2 = $this->createMockPackage($extraVendorPackage2);
        $packages = array($vendorPackage, $vendorPackage2);
        Phake::when($this->localRepository)->getCanonicalPackages()->thenReturn($packages);
        Phake::when($this->rootPackage)->getExtra()->thenReturn($extraRootPackage);

        $this->plugin->onPostUpdate($this->event);

        $this->assertAssetsIsInstalled();
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

    /**
     * @param array $extra
     *
     * @return mixed
     */
    protected function createMockPackage(array $extra)
    {
        $package = Phake::mock('Composer\Package\PackageInterface');
        Phake::when($package)->getExtra()->thenReturn($extra);

        return $package;
    }

    /**
     * @param $dir
     */
    protected function delDir($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file") && !is_link($dir)) ? $this->delDir("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }

    /**
     * Test npm and bower aassets are installed
     */
    protected function assertAssetsIsInstalled()
    {
        $bowerAssets = scandir($this->vendorDir.'/bower_components');
        $this->assertContains('moment', $bowerAssets);
        $this->assertContains('lodash', $bowerAssets);

        $npmAssets = scandir($this->nodeModuleDir);
        $this->assertContains('which', $npmAssets);
        $this->assertContains('test', $npmAssets);
        $this->assertContains('bower', $npmAssets);

        $this->assertFileExists($this->lockFile);
        $this->assertFileExists($this->bowerrcFile);
        $this->assertFileExists($this->packageFile);
        $this->assertFileExists($this->bowerFile);

        $this->assertJsonStringEqualsJsonFile(
            $this->bowerrcFile,
            json_encode(array("directory" => "fake-vendor/bower_components"))
        );
        $this->assertJsonStringEqualsJsonFile(
            $this->bowerFile,
            json_encode(array(
                "name"=> "assets-packages",
                "description" => "This file is auto-generated. Do not change it",
                "dependencies" => array('moment' => '2.0', 'lodash' => '1.0'),
            ))
        );
        $this->assertJsonStringEqualsJsonFile(
            $this->packageFile,
            json_encode(array(
                "name"=> "assets-packages",
                "description" => "This file is auto-generated. Do not change it",
                "version" => "1.0.0",
                "dependencies" => array('which' => '*', 'test' => '~0.5', 'bower' => '*'),
            ))
        );
        $this->assertJsonStringEqualsJsonFile(
            $this->lockFile,
            json_encode(array(
                "name"=> "assets-packages",
                "description" => "This file is auto-generated. Do not change it",
                "npm" => array('which' => '*', 'test' => '~0.5', 'bower' => '*'),
                "bower" => array('moment' => '2.0', 'lodash' => '1.0'),
            ))
        );
    }
}
