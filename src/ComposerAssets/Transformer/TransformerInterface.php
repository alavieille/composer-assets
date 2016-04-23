<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{

    /**
     * @param AssetPackagesInterface $assetPackages
     *
     * @return array
     */
    public function transform(AssetPackagesInterface $assetPackages);
}
