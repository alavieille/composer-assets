<?php

namespace Alav\ComposerAssets\AssetPackages;

/**
 * Class AssetPackagesFactory
 */
class AssetPackagesFactory
{
    protected static $assetPackagesClass = array(
        "npm"   => 'Alav\ComposerAssets\AssetPackages\NpmAssetPackages',
        "bower" => 'Alav\ComposerAssets\AssetPackages\BowerAssetPackages',
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
