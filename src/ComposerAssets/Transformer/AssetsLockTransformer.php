<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\AssetPackages\BowerAssetPackages;
use Alav\ComposerAssets\AssetPackages\NpmAssetPackages;

/**
 * Class AssetsLockTransformer
 *
 * @package Alav\ComposerAssets\Transformer
 */
class AssetsLockTransformer
{
    const DESCRIPTION = "This file is auto-generated. Do not change it";

    /**
     * @param AssetPackagesInterface $npmAssetPackage
     * @param AssetPackagesInterface $bowerAssetPackage
     *
     * @return array
     */
    public function transform(AssetPackagesInterface $npmAssetPackage, AssetPackagesInterface $bowerAssetPackage)
    {
        $json = array();
        $json["name"] = AssetPackagesInterface::NAME_ASSETS;
        $json["description"] = self::DESCRIPTION;
        $json[AssetPackagesInterface::NPM_TYPE] = $npmAssetPackage->getAssets();
        $json[AssetPackagesInterface::BOWER_TYPE] = $bowerAssetPackage->getAssets();

        return $json;
    }

    /**
     * @param array $jsonContent
     *
     * @return array<AssetPackagesInterface>
     */
    public function reverseTransform(array $jsonContent)
    {
        $npmAssetPackage = new NpmAssetPackages();
        $bowerAssetPackage = new BowerAssetPackages();
        if (isset($jsonContent[AssetPackagesInterface::NPM_TYPE])) {
            $npmAssetPackage = $this->reverseTransformAssetPackage($npmAssetPackage, $jsonContent[AssetPackagesInterface::NPM_TYPE]);
        }

        if (isset($jsonContent[AssetPackagesInterface::BOWER_TYPE])) {
            $bowerAssetPackage = $this->reverseTransformAssetPackage($bowerAssetPackage, $jsonContent[AssetPackagesInterface::BOWER_TYPE]);
        }

        return array($npmAssetPackage, $bowerAssetPackage);

    }

    /**
     * @param AssetPackagesInterface $assetPackage
     * @param array                  $assets
     *
     * @return AssetPackagesInterface
     */
    protected function reverseTransformAssetPackage(AssetPackagesInterface $assetPackage, array $assets)
    {
        foreach ($assets as $name => $version) {
            $assetPackage->addAsset($name, $version);
        }

        return $assetPackage;
    }
}
