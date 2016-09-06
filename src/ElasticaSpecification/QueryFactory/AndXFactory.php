<?php

namespace GBProd\ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

/**
 * Factory for AndX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXFactory implements Factory
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
        if (!$spec instanceof AndX) {
            throw new \InvalidArgumentException();
        }

        $firstPartFactory  = $this->registry->getFactory($spec->getFirstPart());
        $secondPartFactory = $this->registry->getFactory($spec->getSecondPart());

        return $qb->query()->bool()
            ->addMust($firstPartFactory->create($spec->getFirstPart(), $qb))
            ->addMust($secondPartFactory->create($spec->getSecondPart(), $qb))
        ;
    }
}
