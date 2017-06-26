<?php

namespace Tests\GBProd\ElasticaSpecification;

use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Tests for registry
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class RegistryTest extends TestCase implements Specification
{
    public function testConstruct()
    {
        $registry = new Registry();

        $this->assertInstanceOf(Registry::class, $registry);
    }

    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }

    public function testgetFactoryThrowsOutOfRangeExceptionIfFactoryNotRegistred()
    {
        $registry = new Registry();

        $this->expectException(\OutOfRangeException::class);

        $registry->getFactory($this);
    }

    public function testgetFactoryReturnsAssociatedFactory()
    {
        $registry = new Registry();

        $factory = $this->prophesize(Factory::class)->reveal();

        $registry->register(self::class, $factory);

        $this->assertEquals(
            $factory,
            $registry->getFactory($this)
        );
    }
}
