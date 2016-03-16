<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Interface AssetPackagesInterface
 */
interface AssetPackagesInterface
{
    const NPM_TYPE = 'npm';
    const BOWER_TYPE = 'bower';

    /**
     * @return array
     */
    public function getAssets();

    /**
     * @param string $name
     * @param string $version
     */
    public function addAsset($name, $version);
}
