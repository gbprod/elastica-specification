<?php

namespace GBProd\ElasticaSpecification\ExpressionBuilder;

use Elastica\QueryBuilder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

/**
 * Expression Builder for AndX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXBuilder implements Builder
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
        if (!$spec instanceof AndX) {
            throw new \InvalidArgumentException();
        }

        $firstPartBuilder  = $this->registry->getBuilder($spec->getFirstPart());
        $secondPartBuilder = $this->registry->getBuilder($spec->getFirstPart());

        return $qb->query()->bool()
            ->addMust($firstPartBuilder->build($spec->getFirstPart(), $qb))
            ->addMust($secondPartBuilder->build($spec->getSecondPart(), $qb))
        ;
    }
}
