<?php

namespace ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\QueryFactory\OrXFactory;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class OrXFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new OrXFactory(new Registry());

        $this->assertInstanceOf(OrXFactory::class, $factory);
    }

    public function testCreateReturnsOrxQuery()
    {
        $orx = $this->createOrX();
        $registry = $this->createRegistry($orx);

        $factory = new OrXFactory($registry);

        $query = $factory->create($orx, new QueryBuilder());

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
        $factory = $this->createMock(Factory::class);
        $factory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($orx->getFirstPart()), $factory);
        $registry->register(get_class($orx->getSecondPart()), $factory);

        return $registry;
    }


    public function testCreateThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new OrXFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec, new QueryBuilder());
    }
}
