<?php

declare(strict_types=1);


namespace GBProd\ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Factory for OrX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class OrXFactory implements Factory
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
        if (!$spec instanceof OrX) {
            throw new \InvalidArgumentException();
        }

        $firstPartFactory  = $this->registry->getFactory($spec->getFirstPart());
        $secondPartFactory = $this->registry->getFactory($spec->getSecondPart());

        return $qb->query()->bool()
            ->addShould($firstPartFactory->create($spec->getFirstPart(), $qb))
            ->addShould($secondPartFactory->create($spec->getSecondPart(), $qb))
        ;
    }
}
