<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;

/**
 * Class BowerTransformer
 */
class BowerTransformer implements TransformerInterface
{
    const DESCRIPTION = "This file is auto-generated. Do not change it";

    /**
     * @param AssetPackagesInterface $assetPackages
     *
     * @return array
     */
    public function transform(AssetPackagesInterface $assetPackages)
    {
        $json = array();
        $json["name"] = AssetPackagesInterface::NAME_ASSETS;
        $json["description"] = self::DESCRIPTION;
        $json["dependencies"] = $assetPackages->getAssets();

        return $json;
    }
}
