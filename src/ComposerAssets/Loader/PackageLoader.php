<?php

namespace Alav\ComposerAssets\Loader;

use Alav\ComposerAssets\AssetPackages\AbstractAssetPackages;
use Alav\ComposerAssets\AssetPackages\AssetPackagesFactory;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;

/**
 * Class PackageLoader
 */
class PackageLoader
{
    const DEV_MARKER = '-dev';
    const KEY_ASSETS = '-assets';

    protected $package;
    protected $vendorPackages;

    /**
     * @param RootPackageInterface $package
     * @param array                $vendorPackages
     */
    public function __construct(RootPackageInterface $package, array $vendorPackages)
    {
        $this->package = $package;
        $this->vendorPackages = $vendorPackages;
    }

    /**
     * @param string $assetType
     * @param bool   $dev
     *
     * @return AbstractAssetPackages
     */
    public function extractAssets($assetType, $dev = false)
    {
        $assetPackages = AssetPackagesFactory::createAssetPackages($assetType);
        $this->extractAssetsPackage($assetPackages, $this->package, $assetType, $dev);
        foreach ($this->vendorPackages as $package) {
            $this->extractAssetsPackage($assetPackages, $package, $assetType, $dev);
        }

        return $assetPackages;
    }

    /**
     * @param AbstractAssetPackages    $assetPackages
     * @param PackageInterface         $package
     * @param string                   $assetType
     * @param boolean                  $dev
     */
    protected function extractAssetsPackage(AbstractAssetPackages $assetPackages, PackageInterface $package, $assetType, $dev)
    {
        $extraPackage = $package->getExtra();
        $this->addAssetRequire($assetPackages, $extraPackage, $assetType.self::KEY_ASSETS);
        if (true === $dev) {
            $this->addAssetRequire($assetPackages, $extraPackage, $assetType.self::KEY_ASSETS.self::DEV_MARKER);
        }
    }

    /**
     * @param AbstractAssetPackages $assetPackages
     * @param array                 $extraPackage
     * @param string                $selectorAsset
     */
    protected function addAssetRequire(AbstractAssetPackages $assetPackages, array $extraPackage, $selectorAsset) {
        if (isset($extraPackage[$selectorAsset])) {
            $assets = $extraPackage[$selectorAsset];
            foreach ($assets as $name => $version) {
                $assetPackages->addAsset($name, $version);
            }
        }
    }
}
