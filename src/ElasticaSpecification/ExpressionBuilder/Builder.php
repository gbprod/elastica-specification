<?php

namespace GBProd\ElasticaSpecification\ExpressionBuilder;

use GBProd\Specification\Specification;
use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;

/**
 * Interface for Elastica Expression Builders
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Builder
{
    /**
     * Build expression for specification
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return AbstractQuery
     */
    public function build(Specification $spec, QueryBuilder $qb);
}
