<?php

namespace GBProd\ElasticaSpecification\QueryFactory;

use GBProd\Specification\Specification;
use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;

/**
 * Interface for Elastica query factory
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Factory
{
    /**
     * Create query for specification
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return AbstractQuery
     */
    public function create(Specification $spec, QueryBuilder $qb);
}
