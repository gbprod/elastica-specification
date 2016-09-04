<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

class AndXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $builder = new AndXBuilder(new Registry());

        $this->assertInstanceOf(AndXBuilder::class, $builder);
    }

    public function testBuildReturnsAndxExpression()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistry($andx);

        $builder = new AndXBuilder($registry);

        $query = $builder->build($andx, new QueryBuilder());

        $this->assertInstanceOf(BoolQuery::class, $query);

        $this->assertArrayHasKey('bool', $query->toArray());
        $this->assertArrayHasKey('must', $query->toArray()['bool']);
        $this->assertCount(2, $query->toArray()['bool']['must']);
    }

    /**
     * @return AndX
     */
    private function createAndX()
    {
        return new AndX(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );
    }

    /**
     * @param AndX $andx
     *
     * @return Registry
     */
    private function createRegistry($andx)
    {
        $builder = $this->createMock(Builder::class);
        $builder
            ->expects($this->any())
            ->method('build')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($andx->getFirstPart()), $builder);
        $registry->register(get_class($andx->getSecondPart()), $builder);

        return $registry;
    }


    public function testBuildThrowExceptionIfNotAndXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $builder = new AndXBuilder($registry);

        $this->setExpectedException(\InvalidArgumentException::class);

        $expr = $builder->build($spec, new QueryBuilder());
    }
}
