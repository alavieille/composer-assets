<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;

/**
 * Class NpmTransformer
 */
class NpmTransformer implements TransformerInterface
{
    const NAME    = "npm-assets-packages";
    const VERSION = "1.0.0";
    const DESCRIPTION = "This file is auto-generated. Do not change it";

    /**
     * @param AssetPackagesInterface $assetPackages
     *
     * @return array
     */
    public function transform(AssetPackagesInterface $assetPackages)
    {
        $json = array();
        $json["name"] = self::NAME;
        $json["description"] = self::DESCRIPTION;
        $json["version"] = self::VERSION;
        $json["dependencies"] = $assetPackages->getAssets();

        return $json;
    }
}
