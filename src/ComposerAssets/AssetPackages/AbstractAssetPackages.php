<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Class AbstractAssetPackages
 */
abstract class AbstractAssetPackages implements AssetPackagesInterface
{
    protected $assets = array();

    /**
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param string $name
     * @param string $version
     */
    public function addAsset($name, $version)
    {
        if (isset($this->assets[$name])) {
            $this->assets[$name] .= ' ' . $version;
        } else {
            $this->assets[$name] = $version;
        }
    }
}
