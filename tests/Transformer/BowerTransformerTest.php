<?php

namespace Alav\ComposerAssets\Tests\Transformer;

use Alav\ComposerAssets\Transformer\BowerTransformer;
use Phake;

/**
 * Class BowerTransformerTest
 */
class BowerTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  BowerTransformer */
    protected $transformer;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->transformer = new BowerTransformer();
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
        $bowerPackage = Phake::mock('Alav\ComposerAssets\AssetPackages\AssetPackagesInterface');
        Phake::when($bowerPackage)->getAssets()->thenReturn($assets);

        $transformPackage = $this->transformer->transform($bowerPackage);

        $this->assertSame(BowerTransformer::NAME, $transformPackage['name']);
        $this->assertSame(BowerTransformer::DESCRIPTION, $transformPackage['description']);
        $this->assertSame($assets, $transformPackage['dependencies']);
    }
}
