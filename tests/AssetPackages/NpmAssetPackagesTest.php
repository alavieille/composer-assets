<?php

namespace Alav\ComposerAssets\Tests\AssetPackages;

use Alav\ComposerAssets\AssetPackages\NpmAssetPackages;

/**
 * Class NpmAssetPackagesTest
 */
class NpmAssetPackagesTest extends AbstractAssetPackagesTest
{
    /**
     * Set Up
     */
    public function setUp()
    {
        $this->packages = new NpmAssetPackages();
    }
}
