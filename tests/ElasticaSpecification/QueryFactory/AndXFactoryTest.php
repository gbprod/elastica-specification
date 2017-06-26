<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\QueryFactory\AndXFactory;
use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class AndXFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new AndXFactory(new Registry());

        $this->assertInstanceOf(AndXFactory::class, $factory);
    }

    public function testCreateReturnsAndxQuery()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistry($andx);

        $factory = new AndXFactory($registry);

        $query = $factory->create($andx, new QueryBuilder());

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
        $factory = $this->createMock(Factory::class);
        $factory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($andx->getFirstPart()), $factory);
        $registry->register(get_class($andx->getSecondPart()), $factory);

        return $registry;
    }


    public function testCreateThrowExceptionIfNotAndXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new AndXFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec, new QueryBuilder());
    }
}
