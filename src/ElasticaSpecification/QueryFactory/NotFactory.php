<?php

namespace GBProd\ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

/**
 * Factory for Not specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class NotFactory implements Factory
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
    public function create(Specification $spec, QueryBuilder $qb)
    {
        if (!$spec instanceof Not) {
            throw new \InvalidArgumentException();
        }

        $firstPartFactory = $this->registry->getFactory($spec->getWrappedSpecification());

        return $qb->query()->bool()
            ->addMustNot($firstPartFactory->create($spec->getWrappedSpecification(), $qb))
        ;
    }
}
