<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\ExpressionBuilder\OrXBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class OrXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $builder = new OrXBuilder(new Registry());

        $this->assertInstanceOf(OrXBuilder::class, $builder);
    }

    public function testBuildReturnsOrxExpression()
    {
        $orx = $this->createOrX();
        $registry = $this->createRegistry($orx);

        $builder = new OrXBuilder($registry);

        $query = $builder->build($orx, new QueryBuilder());

        $this->assertInstanceOf(BoolQuery::class, $query);

        $this->assertArrayHasKey('bool', $query->toArray());
        $this->assertArrayHasKey('should', $query->toArray()['bool']);
        $this->assertCount(2, $query->toArray()['bool']['should']);
    }

    /**
     * @return OrX
     */
    private function createOrX()
    {
        return new OrX(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );
    }

    /**
     * @param OrX $orx
     *
     * @return Registry
     */
    private function createRegistry($orx)
    {
        $builder = $this->createMock(Builder::class);
        $builder
            ->expects($this->any())
            ->method('build')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($orx->getFirstPart()), $builder);
        $registry->register(get_class($orx->getSecondPart()), $builder);

        return $registry;
    }


    public function testBuildThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $builder = new OrXBuilder($registry);

        $this->expectException(\InvalidArgumentException::class);

        $expr = $builder->build($spec, new QueryBuilder());
    }
}
