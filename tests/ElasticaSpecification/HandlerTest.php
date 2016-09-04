<?php

namespace Tests\GBProd\ElasticaSpecification;

use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use GBProd\ElasticaSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\ElasticaSpecification\ExpressionBuilder\NotBuilder;
use GBProd\ElasticaSpecification\ExpressionBuilder\OrXBuilder;
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
     * @var Handler
     */
    private $handler;

    protected function setUp()
    {
        $this->registry = new Registry();
        $this->handler = new Handler($this->registry);
    }

    public function testConstructWillRegisterBaseBuilders()
    {
        $spec1 = $this->createMock(Specification::class);
        $spec2 = $this->createMock(Specification::class);

        $this->assertInstanceOf(
            AndXBuilder::class,
            $this->registry->getBuilder(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXBuilder::class,
            $this->registry->getBuilder(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotBuilder::class,
            $this->registry->getBuilder(new Not($spec1))
        );
    }

    public function testRegisterBuilderAddBuilderInRegistry()
    {
        $builder = $this->createMock(Builder::class);
        $spec = $this->createMock(Specification::class);

        $this->handler->registerBuilder(get_class($spec), $builder);

        $this->assertEquals(
            $builder,
            $this->registry->getBuilder($spec)
        );
    }

    public function testHandle()
    {
        $this->handler = new Handler(new Registry());

        $builder = $this->prophesize(Builder::class);

        $spec = $this->createMock(Specification::class);
        $this->handler->registerBuilder(get_class($spec), $builder->reveal());

        $builtQuery = $this->getMockForAbstractClass(AbstractQuery::class);
        $qb = new QueryBuilder();

        $builder
            ->build($spec, $qb)
            ->willReturn($builtQuery)
            ->shouldBeCalled()
        ;

        $this->assertEquals(
            $builtQuery,
            $this->handler->handle($spec, $qb)
        );
    }
}
