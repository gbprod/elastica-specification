<?php

namespace ElasticaSpecification\QueryFactory;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use GBProd\ElasticaSpecification\QueryFactory\NotFactory;
use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

class NotFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $factory = new NotFactory(new Registry());

        $this->assertInstanceOf(NotFactory::class, $factory);
    }

    public function testCreateReturnsNotQuery()
    {
        $not = $this->createNot();
        $registry = $this->createRegistry($not);

        $factory = new NotFactory($registry);

        $query = $factory->create($not, new QueryBuilder());

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
        $factory = $this->createMock(Factory::class);
        $factory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->createMock(AbstractQuery::class))
        ;

        $registry = new Registry();

        $registry->register(get_class($not->getWrappedSpecification()), $factory);

        return $registry;
    }


    public function testCreateThrowExceptionIfNotNotSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new NotFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $factory->create($spec, new QueryBuilder());
    }
}
