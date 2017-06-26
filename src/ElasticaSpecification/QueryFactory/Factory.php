<?php

declare(strict_types=1);

namespace GBProd\ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use GBProd\Specification\Specification;

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
