<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Interface AssetPackagesInterface
 */
interface AssetPackagesInterface
{
    const NPM_TYPE = 'npm';
    const BOWER_TYPE = 'bower';
    const NAME_ASSETS = "assets-packages";

    /**
     * @return array
     */
    public function getAssets();

    /**
     * @param string $name
     * @param string $version
     */
    public function addAsset($name, $version);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasAsset($name);
}
