<?php

namespace Alav\ComposerAssets\Tests\AssetPackages;

use Alav\ComposerAssets\AssetPackages\BowerAssetPackages;

/**
 * Class BowerAssetPackagesTest
 */
class BowerAssetPackagesTest extends AbstractAssetPackagesTest
{
    /**
     * Set Up
     */
    public function setUp()
    {
        $this->packages = new BowerAssetPackages();
    }
}
