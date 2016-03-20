<?php

namespace Alav\ComposerAssets\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\AssetPackages\Facade\BowerFacade;
use Alav\ComposerAssets\AssetPackages\Facade\FacadeInterface;

/**
 * Class BowerTransformer
 */
class BowerTransformer implements TransformerInterface
{
    /**
     * @param AssetPackagesInterface $assetPackages
     *
     * @return FacadeInterface
     */
    public function transform(AssetPackagesInterface $assetPackages)
    {
        $facade = new BowerFacade();

        $facade->name = BowerFacade::NAME;
        $facade->description = FacadeInterface::DESCRIPTION;
        $facade->dependencies = $assetPackages->getAssets();

        return $facade;
    }
}
