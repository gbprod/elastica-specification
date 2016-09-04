# Elastica specification

[![Build Status](https://travis-ci.org/gbprod/elastica-specification.svg?branch=master)](https://travis-ci.org/gbprod/elastica-specification)
[![codecov](https://codecov.io/gh/gbprod/elastica-specification/branch/master/graph/badge.svg)](https://codecov.io/gh/gbprod/elastica-specification)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gbprod/elastica-specification/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gbprod/elastica-specification/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/574a9bc8ce8d0e004130d330/badge.svg)](https://www.versioneye.com/user/projects/574a9bc8ce8d0e004130d330)

[![Latest Stable Version](https://poser.pugx.org/gbprod/elastica-specification/v/stable)](https://packagist.org/packages/gbprod/elastica-specification)
[![Total Downloads](https://poser.pugx.org/gbprod/elastica-specification/downloads)](https://packagist.org/packages/gbprod/elastica-specification)
[![Latest Unstable Version](https://poser.pugx.org/gbprod/elastica-specification/v/unstable)](https://packagist.org/packages/gbprod/elastica-specification)
[![License](https://poser.pugx.org/gbprod/elastica-specification/license)](https://packagist.org/packages/gbprod/elastica-specification)

This library allows you to create Elastica queries using the [specification pattern](http://en.wikipedia.org/wiki/Specification_pattern).

## Usage

You can write specifications using [gbprod/specification](https://github.com/gbprod/specification) library.

### Creates a elastica specification filter

```php
namespace GBProd\Acme\Elastica\SpecificationBuilder;

use GBProd\ElasticaSpecification\ExpressionBuilder\Builder;
use GBProd\Specification\Specification;
use Elastica\QueryBuilder;

class IsAvailableBuilder implements Builder
{
    public function build(Specification $spec, QueryBuilder $qb)
    {
        return $qb->query()->bool()
            ->addMust(
                $qb->query()->term(['available' => "0"]),
            )
        ;
    }
}
```

### Configure

```php
$registry = new GBProd\ElasticaSpecification\Registry();

$handler = new GBProd\ElasticaSpecification\Handler($registry);
$handler->registerBuilder(IsAvailable::class, new IsAvailableBuilder());
$handler->registerBuilder(StockGreaterThan::class, new StockGreaterThanBuilder());
```

### Use it

```php
$available = new IsAvailable();
$hightStock = new StockGreaterThan(4);

$availableWithLowStock = $available
    ->andX(
        $hightStock->not()
    )
;

$type = $this->elasticaClient
    ->getIndex('my_index')
    ->getType('my_type')
;
$qb = new \Elastica\QueryBuilder();

$query = $handler->handle($availableWithLowStock, $qb)

$results = $type->search($query);
```

## Requirements

 * PHP 5.5+

## Installation

### Using composer

```bash
composer require gbprod/elastica-specification
```
