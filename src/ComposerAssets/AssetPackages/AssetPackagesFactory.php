<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Class AssetPackagesFactory
 */
class AssetPackagesFactory
{
    protected static $assetPackagesClass = array(
        AssetPackagesInterface::NPM_TYPE   => 'Alav\ComposerAssets\AssetPackages\NpmAssetPackages',
        AssetPackagesInterface::BOWER_TYPE => 'Alav\ComposerAssets\AssetPackages\BowerAssetPackages',
    );

    /**
     * @param string $assetType
     *
     * @return AbstractAssetPackages
     */
    public static function createAssetPackages($assetType)
    {
        return new self::$assetPackagesClass[$assetType];
    }
}
