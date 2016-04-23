<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\AssetPackages\Facade\FacadeInterface;

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
