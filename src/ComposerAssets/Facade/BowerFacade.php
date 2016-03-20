<?php

namespace Alav\ComposerAssets\AssetPackages\Facade;

/**
 * Class BowerFacade
 */
class BowerFacade implements FacadeInterface
{
    public $name;

    public $description;

    public $version;

    public $dependencies = array();
}
