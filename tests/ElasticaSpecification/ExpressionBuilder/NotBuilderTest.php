<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\ExpressionBuilder\NotBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

class NotBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $builder = new NotBuilder(new Registry());

        $this->assertInstanceOf(NotBuilder::class, $builder);
    }

    public function testBuildReturnsNotExpression()
    {
        $not = $this->createNot();
        $registry = $this->createRegistry($not);

        $builder = new NotBuilder($registry);

        $query = $builder->build($not, new QueryBuilder());

        $this->assertInstanceOf(BoolQuery::class, $query);

        $this->assertArrayHasKey('bool', $query->toArray());
        $this->assertArrayHasKey('must_not', $query->toArray()['bool']);
        $this->assertCount(1, $query->toArray()['bool']['must_not']);
    }

    /**
     * @return Not
     */
    private function createNot()
    {
        return new Not(
            $this->createMock(Specification::class)
        );
    }

    /**
     * @param Not $not
     *
     * @return Registry
     */
    private function createRegistry($not)
    {
        $builder = $this->createMock(Builder::class);
        $builder
            ->expects($this->any())
            ->method('build')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($not->getWrappedSpecification()), $builder);

        return $registry;
    }


    public function testBuildThrowExceptionIfNotNotSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $builder = new NotBuilder($registry);

        $this->expectException(\InvalidArgumentException::class);

        $builder->build($spec, new QueryBuilder());
    }
}
