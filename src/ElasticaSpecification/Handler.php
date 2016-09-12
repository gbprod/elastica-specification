<?php

namespace GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use GBProd\ElasticaSpecification\QueryFactory\AndXFactory;
use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\QueryFactory\NotFactory;
use GBProd\ElasticaSpecification\QueryFactory\OrXFactory;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Handler for elastica specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Handler
{
    /**
     * @param Registry
     */
    private $registry;

    /**
     * @param QueryBuilder
     */
    private $qb;

    /**
     * @param Registry     $registry
     * @param QueryBuilder $qb
     */
    public function __construct(Registry $registry, QueryBuilder $qb)
    {
        $this->qb = $qb;
        $this->registry = $registry;

        $this->registry->register(AndX::class, new AndXFactory($registry));
        $this->registry->register(OrX::class, new OrXFactory($registry));
        $this->registry->register(Not::class, new NotFactory($registry));
    }

    /**
     * handle specification for querybuilder
     *
     * @param Specification $spec
     *
     * @return AbstractQuery
     */
    public function handle(Specification $spec)
    {
        $factory = $this->registry->getFactory($spec);

        return $factory->create($spec, $this->qb);
    }

    /**
     * Register a factory for specification
     *
     * @param string  $classname specification fully qualified classname
     * @param Factory $factory
     */
    public function registerFactory($classname, Factory $factory)
    {
        $this->registry->register($classname, $factory);
    }
}
