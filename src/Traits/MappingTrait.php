<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use Jasny\IteratorPipeline\Pipeline;
use Jasny\Iterator\CombineIterator;

/**
 * Mapping and projection methods for iterator pipeline
 */
trait MappingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return $this
     */
    abstract public function then(callable $callback, ...$args);


    /**
     * Map each element to a value using a callback function.
     *
     * @param callable $callback
     * @return $this
     */
    public function map(callable $callback)
    {
        return $this->then('Jasny\iterable_map', $callback);
    }

    /**
     * Map the key of each element to a new key using a callback function.
     *
     * @param callable $callback
     * @return $this
     */
    public function mapKeys(callable $callback)
    {
        return $this->then('Jasny\iterable_map_keys', $callback);
    }

    /**
     * Apply a callback to each element of an iterator.
     * Any value returned by the callback is ignored.
     *
     * @param callable $callback
     * @return $this
     */
    public function apply(callable $callback)
    {
        return $this->then('Jasny\iterable_apply', $callback);
    }

    /**
     * Group elements of an iterator, with the group name as key and an array of elements as value.
     *
     * @param callable $grouping
     * @return $this
     */
    public function group(callable $grouping)
    {
        return $this->then('Jasny\iterable_group', $grouping);
    }

    /**
     * Walk through all sub-iterables and concatenate them.
     *
     * @return $this
     */
    public function flatten()
    {
        return $this->then('Jasny\iterable_flatten');
    }

    /**
     * Project each element of an iterator to an associated (or numeric) array. Each element should be an array or object.
     *
     * @param array $mapping  [new key => old key, ...]
     * @return $this
     */
    public function project(array $mapping)
    {
        return $this->then('Jasny\iterable_project', $mapping);
    }


    /**
     * Keep the values, drop the keys. The keys become an incremental number.
     *
     * @return $this
     */
    public function values()
    {
        return $this->then('Jasny\iterable_values');
    }

    /**
     * Use the keys as values. The keys become an incremental number.
     *
     * @return $this
     */
    public function keys()
    {
        return $this->then('Jasny\iterable_keys');
    }

    /**
     * Use another iterator as keys and the current iterator as values.
     *
     * @param iterable $keys
     * @return $this
     */
    public function setKeys(iterable $keys)
    {
        $combine = function($values, $keys) {
            return new CombineIterator($keys, $values);
        };

        return $this->then($combine, $keys);
    }

    /**
     * Use values as keys and visa versa.
     *
     * @return Pipeline
     */
    public function flip()
    {
        return $this->then('Jasny\iterable_flip');
    }
}
