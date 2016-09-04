<?php

namespace GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use GBProd\ElasticaSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\ExpressionBuilder\NotBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\OrXBuilder;
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
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->registry->register(AndX::class, new AndXBuilder($registry));
        $this->registry->register(OrX::class, new OrXBuilder($registry));
        $this->registry->register(Not::class, new NotBuilder($registry));
    }

    /**
     * handle specification for querybuilder
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return AbstractQuery
     */
    public function handle(Specification $spec, QueryBuilder $qb)
    {
        $builder = $this->registry->getBuilder($spec);

        return $builder->build($spec, $qb);
    }

    /**
     * Register a builder for specification
     *
     * @param string  $classname specification fully qualified classname
     * @param Builder $builder
     */
    public function registerBuilder($classname, Builder $builder)
    {
        $this->registry->register($classname, $builder);
    }
}
