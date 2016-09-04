<?php

namespace GBProd\ElasticaSpecification\ExpressionBuilder;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

/**
 * Expression Builder for Not specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class NotBuilder implements Builder
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {inheritdoc}
     */
    public function build(Specification $spec, QueryBuilder $qb)
    {
        if (!$spec instanceof Not) {
            throw new \InvalidArgumentException();
        }

        $firstPartBuilder  = $this->registry->getBuilder($spec->getWrappedSpecification());

        return $qb->query()->bool()
            ->addMustNot($firstPartBuilder->build($spec->getWrappedSpecification(), $qb))
        ;
    }
}
