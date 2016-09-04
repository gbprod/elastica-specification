<?php

namespace Tests\GBProd\ElasticaSpecification;

use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Specification;

/**
 * Tests for registry
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class RegistryTest extends \PHPUnit_Framework_TestCase implements Specification
{
    public function testConstruct()
    {
        $registry = new Registry();

        $this->assertInstanceOf(Registry::class, $registry);
    }

    public function isSatisfiedBy($candidate)
    {
        return true;
    }

    public function testGetBuilderThrowsOutOfRangeExceptionIfBuilderNotRegistred()
    {
        $registry = new Registry();

        $this->expectException(\OutOfRangeException::class);

        $registry->getBuilder($this);
    }

    public function testGetBuilderReturnsAssociatedBuilder()
    {
        $registry = new Registry();

        $builder = $this->prophesize(Builder::class)->reveal();

        $registry->register(self::class, $builder);

        $this->assertEquals(
            $builder,
            $registry->getBuilder($this)
        );
    }
}
