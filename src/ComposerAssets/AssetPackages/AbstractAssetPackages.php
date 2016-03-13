<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Class AbstractAssetPackages
 */
abstract class AbstractAssetPackages
{
    protected $assets = array();

    /**
     * @param string $name
     * @param string $version
     */
    public function addAsset($name, $version)
    {
        $this->assets[$name] = $version;
    }
}
