<?php

declare(strict_types=1);

namespace Jasny\PipelineIterator\Tests;

use Jasny\IteratorPipeline\Pipeline;
use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Pipeline
 */
class PipelineTest extends TestCase
{
    use TestHelper;

    public function iterableProvider()
    {
        $generator = function($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $tests = [
            [$values, $values],
            [new \ArrayIterator($values), $values],
            [new \ArrayObject($values), $values],
            [$generator($values), $values]
        ];

        return $tests;
    }

    /**
     * @dataProvider iterableProvider
     */
    public function testThen($next, $expected)
    {
        $input = new \ArrayIterator(['foo']);

        $pipeline = new Pipeline($input);

        $ret = $pipeline->then(function($iterable) use ($next) {
            $this->assertSame($iterable, $iterable);
            return $next;
        });

        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals($expected, $result);
    }


    public function provider()
    {
        $generator = function($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $tests = [
            [$values, $values],
            [new \ArrayIterator($values), $values],
            [new \ArrayObject($values), $values],
            [$generator($values), $values]
        ];

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function testToArray($values, $expected)
    {
        $pipeline = new Pipeline($values);
        $array = $pipeline->toArray();

        $this->assertEquals($expected, $array);
    }

    /**
     * @dataProvider provider
     */
    public function testGetIterator($values, $expected)
    {
        $pipeline = new Pipeline($values);
        $this->assertInstanceOf(\Traversable::class, $pipeline);

        $iterator = $pipeline->getIterator();

        $this->assertInstanceOf(\Iterator::class, $iterator);
        $this->assertEquals($expected, iterator_to_array($iterator, true));
    }

    /**
     * @dataProvider provider
     */
    public function testWith($values)
    {
        $pipeline = Pipeline::with($values);
        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $this->assertAttributeSame($values, 'iterable', $pipeline);
    }
}
