<?php

declare(strict_types=1);

namespace GBProd\ElasticaSpecification;

use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\Specification\Specification;

/**
 * Registry class for factories
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Registry
{
    /**
     * @var array<Factory>
     */
    private $factories = [];

    /**
     * Register a Factory
     *
     * @param string classname Fully qualified classname of the handled specification
     * @param Factory $factory
     */
    public function register($classname, Factory $factory)
    {
        $this->factories[$classname] = $factory;
    }

    /**
     * Get registred Factory for Specification
     *
     * @param Specification $spec
     *
     * @return Factory
     *
     * @throw OutOfRangeException if Factory not found
     */
    public function getFactory(Specification $spec): Factory
    {
        if (!isset($this->factories[get_class($spec)])) {
            throw new \OutOfRangeException(sprintf(
                'Factory for Specification "%s" not registred',
                get_class($spec)
            ));
        }

        return $this->factories[get_class($spec)];
    }
}
