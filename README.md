Jasny Iterator
===

[![Build Status](https://travis-ci.org/jasny/iterator.svg?branch=master)](https://travis-ci.org/jasny/iterator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/iterator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/iterator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/iterator/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/iterator.svg)](https://packagist.org/packages/jasny/iterator)
[![Packagist License](https://img.shields.io/packagist/l/jasny/iterator.svg)](https://packagist.org/packages/jasny/iterator)

A set of useful [iterators](http://php.net/manual/en/class.iterator.php) for PHP.

Installation
---

    composer require jasny/iterator

Usage
---

* [MapIterator](#mapiterator)
* [MapKeyIterator](#mapkeyiterator)
* [ApplyIterator](#applyiterator)
* [DistinctIterator](#distinctiterator)
* [SortIterator](#sortiterator)
* [SortKeyIterator](#sortkeyiterator)
* [FlattenIterator](#flatteniterator)
* [ValueIterator](#valueiterator)
* [KeyIterator](#keyiterator)

### MapIterator

Map all elements of an Iterator. The keys remain unchanged.

```php
$persons = new \ArrayIterator([
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
]);

$iterator = new MapIterator($persons, function(Person $person, $role) {
    return sprintf("%s = %s", $role, $person->name);
});
```

### MapKeyIterator

Map all keys elements of an Iterator. The values remain unchanged.

```php
$persons = new \ArrayIterator([
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
]);

$iterator = new MapKeyIterator($persons, function($role, $person) {
    return sprintf("%s (%s)", $person->name, $role);
});
```

_Note: The callback function switches value and key arguments, so the first argument is the key._

### ApplyIterator

Apply a callback to each element. This is a pass-through iterator, any value returned by the callback is ignored.

```php
$persons = new \ArrayIterator([
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
]);

$iterator = new ApplyIterator($persons, function(Person $person, $role) {
    $value->role = $role;
});
```

### DistinctIterator

Filter to get only unique items. The keys are preserved, skipping duplicate values.

```php
$values = new \ArrayIterator(['foo', 'bar', 'qux', 'foo', 'zoo']);

$iterator = new DistinctIterator($values);
```

You can pass a callback, which should return a value. Filtering on distinct values will be based on that value.

```php
$persons = new \ArrayIterator([
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
]);

$iterator = new DistinctIterator($persons, function(Person $person) {
    return $person->age;
});
```

All values are stored for reference. The callback function can also be used to serialize and hash the value.

```php
$persons = new \ArrayIterator([
    'client' => new Person("Max", 18),
    'seller' => new Person("Peter", 23),
    'lawyer' => new Person("Pamela", 23)
]);

$iterator = new DistinctIterator($persons, function(Person $person) {
    return hash('sha256', serialize($person));
});
```

The keys of an iterator don't have to be unique. This is unlike an associated array. You may return the key in the
callback to get distinct keys.

```php
$iterator = new DistinctIterator($someGenerator, function($value, $key) {
    return $key;
});
```

### SortIterator

Sort all elements of an iterator.

```php
$values = new \ArrayIterator(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"]);

$iterator = new SortIterator($values);
```

Instead of using the default `asort()`, a callback may be passed as user defined comparison function.

```php
$values = new \ArrayIterator(["Charlie", "Echo", "Bravo", "Delta", "Foxtrot", "Alpha"]);

$iterator = new SortIterator($values, function($a, $b) {
    return strlen($a) <=> strlen($b);
});
```

The keys are preserved.

_Unlike other iterators, the `SortIterator` may require traversing through all elements an putting them in an
`ArrayIterator` for sorting._


### SortKeyIterator

Sort all elements of an iterator based on the key.

```php
$values = new \ArrayIterator([
    "Charlie" => "three",
    "Bravo" => "two",
    "Delta" => "four",
    "Alpha" => "one"
]);

$iterator = new SortKeyIterator($values);
```

Similar to `SortIterator`, a callback may be passed. 

### FlattenIterator

Walk through all sub-iterables and concatenate them.

```php
$values = new \ArrayIterator([
    ['one', 'two'],
    ['three', 'four', 'five'],
    [],
    ['six']
]);

$iterator = new FlattenIterator($values);
```

The entries may be an array, Iterator or IteratorAggregate.

```php
$values = new \ArrayIterator([
    new \ArrayIterator(['one', 'two']),
    new \ArrayObject(['three', 'four', 'five']),
    new \EmptyIterator(),
    ['six']
]);

$iterator = new FlattenIterator($values);
```

By default the keys are dropped, replaces by an incrementing counter (so as an numeric array). By passing `true` as
second parameters, the keys are remained.

```php
$values = new \ArrayIterator([
    ['one' => 'uno', 'two' => 'dos'],
    ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
    [],
    ['six' => 'seis']
]);

$iterator = new FlattenIterator($values, true);
```

### ValueIterator

Keep the values, drop the keys. The keys become an incremental number. This is comparable to
[`array_values`](https://php.net/array_values).

```php
$values = new \ArrayIterator(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro']);

$spanish = new ValueIterator($values);
```

### KeyIterator

Use the keys as values. The keys become an incremental number. This is comparable to
[`array_keys`](https://php.net/array_keys).

```php
$values = new \ArrayIterator(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro']);

$spanish = new KeyIterator($values);
```
