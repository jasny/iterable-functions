<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Apply a callback to each element.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iteratable_apply(iterable $iterable, callable $callback): \Generator
{
    foreach ($iterable as $key => $value) {
        call_user_func($callback, $value, $key);

        yield $key => $value;
    }
}
