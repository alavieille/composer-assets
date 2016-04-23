<?php

namespace Alav\ComposerAssets\Tests\Transformer;

use Alav\ComposerAssets\AssetPackages\AssetPackagesInterface;
use Alav\ComposerAssets\Transformer\NpmTransformer;
use Phake;

/**
 * Class NpmTransformerTest
 */
class NpmTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  NpmTransformer */
    protected $transformer;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->transformer = new NpmTransformer();
    }

    /**
     * Test interface
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Alav\ComposerAssets\Transformer\TransformerInterface', $this->transformer);
    }

    /**
     * Test transform
     */
    public function testTransform()
    {
        $assets = array(
            "fakeAssets" => "fakeVersion"
        );
        $npmPackage = Phake::mock('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface');
        Phake::when($npmPackage)->getAssets()->thenReturn($assets);

        $transformPackage = $this->transformer->transform($npmPackage);

        $this->assertSame(AssetPackagesInterface::NAME_ASSETS, $transformPackage['name']);
        $this->assertSame(NpmTransformer::DESCRIPTION, $transformPackage['description']);
        $this->assertSame(NpmTransformer::VERSION, $transformPackage['version']);
        $this->assertSame($assets, $transformPackage['dependencies']);
    }
}
