<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\AssetPackages\Facade\FacadeInterface;
use Alav\ComposerAssets\AssetPackages\Facade\NpmFacade;

/**
 * Class NpmTransformer
 */
class NpmTransformer implements TransformerInterface
{
    /**
     * @param AssetPackagesInterface $assetPackages
     *
     * @return FacadeInterface
     */
    public function transform(AssetPackagesInterface $assetPackages)
    {
        $facade = new NpmFacade();

        return $facade;
    }
}
