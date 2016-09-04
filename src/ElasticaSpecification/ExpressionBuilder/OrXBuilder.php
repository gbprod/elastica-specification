<?php

namespace GBProd\ElasticaSpecification\ExpressionBuilder;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Expression Builder for OrX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class OrXBuilder implements Builder
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
        if (!$spec instanceof OrX) {
            throw new \InvalidArgumentException();
        }

        $firstPartBuilder  = $this->registry->getBuilder($spec->getFirstPart());
        $secondPartBuilder = $this->registry->getBuilder($spec->getFirstPart());

        return $qb->query()->bool()
            ->addShould($firstPartBuilder->build($spec->getFirstPart(), $qb))
            ->addShould($secondPartBuilder->build($spec->getSecondPart(), $qb))
        ;
    }
}
