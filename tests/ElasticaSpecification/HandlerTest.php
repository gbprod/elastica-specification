<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use GBProd\ElasticaSpecification\QueryFactory\AndXFactory;
use GBProd\ElasticaSpecification\QueryFactory\Factory;
use GBProd\ElasticaSpecification\QueryFactory\NotFactory;
use GBProd\ElasticaSpecification\QueryFactory\OrXFactory;
use GBProd\ElasticaSpecification\Handler;
use GBProd\ElasticaSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @var Handler
     */
    private $handler;

    protected function setUp()
    {
        $this->registry = new Registry();
        $this->qb = new QueryBuilder();
        $this->handler = new Handler($this->registry, $this->qb);
    }

    public function testConstructWillRegisterBaseFactoriess()
    {
        $spec1 = $this->createMock(Specification::class);
        $spec2 = $this->createMock(Specification::class);

        $this->assertInstanceOf(
            AndXFactory::class,
            $this->registry->getFactory(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXFactory::class,
            $this->registry->getFactory(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotFactory::class,
            $this->registry->getFactory(new Not($spec1))
        );
    }

    public function testRegisterFactoryAddFactoryInRegistry()
    {
        $factory = $this->createMock(Factory::class);
        $spec = $this->createMock(Specification::class);

        $this->handler->registerFactory(get_class($spec), $factory);

        $this->assertEquals(
            $factory,
            $this->registry->getFactory($spec)
        );
    }

    public function testHandle()
    {
        $factory = $this->prophesize(Factory::class);

        $spec = $this->createMock(Specification::class);
        $this->handler->registerFactory(get_class($spec), $factory->reveal());

        $builtQuery = $this->getMockForAbstractClass(AbstractQuery::class);

        $factory
            ->create($spec, $this->qb)
            ->willReturn($builtQuery)
            ->shouldBeCalled()
        ;

        $this->assertEquals(
            $builtQuery,
            $this->handler->handle($spec)
        );
    }
}
