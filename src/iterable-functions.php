<?php

if (false === defined('ITERABLE_FILTER_USE_BOTH')) {
    define('ITERABLE_FILTER_USE_BOTH', ARRAY_FILTER_USE_BOTH);
}
if (false === defined('ITERABLE_FILTER_USE_KEY')) {
    define('ITERABLE_FILTER_USE_KEY', ARRAY_FILTER_USE_KEY);
}

if (false === function_exists('iterable_to_array')) {
    function iterable_to_array(iterable $iterable, bool $preserve_keys = true): array
    {
        if ($iterable instanceof Traversable) {
            return iterator_to_array($iterable, $preserve_keys);
        }

        return $preserve_keys ? $iterable : array_values($iterable);
    }
}

if (false === function_exists('iterable_to_generator')) {
    function iterable_to_generator(iterable $iterable): Generator
    {
        yield from $iterable;
    }
}

if (false === function_exists('iterable_count')) {
    function iterable_count(iterable $iterable): int
    {
        if ($iterable instanceof Traversable) {
            return iterator_count($iterable);
        }

        return count($iterable);
    }
}

if (false === function_exists('iterable_merge')) {
    function iterable_merge(iterable ...$iterables): iterable
    {
        foreach ($iterables as $iterable) {
            yield from $iterable;
        }
    }
}

if (false === function_exists('iterable_filter')) {
    function iterable_filter(iterable $iterable, ?callable $callback, int $mode = 0): iterable
    {
        $callback ??= static fn ($value): bool => !empty($value);
        foreach ($iterable as $key => $value) {
            $args = match ($mode) {
                ITERABLE_FILTER_USE_BOTH => [$key, $value],
                ITERABLE_FILTER_USE_KEY => [$key],
                default => [$value],
            };

            if (true === $callback(...$args)) {
                yield $key => $value;
            }
        }
    }
}

if (false === function_exists('iterable_map')) {
    function iterable_map(iterable $iterable, ?callable $callback): iterable
    {
        $callback ??= static fn ($value) => $value;
        foreach ($iterable as $key => $value) {
            yield $key => $callback($value);
        }
    }
}

if (false === function_exists('iterable_chunk')) {
    function iterable_chunk(iterable $iterable, int $length, bool $preserve_keys = true): iterable
    {
        $iterator = iterable_to_generator($iterable);
        $chunk = static function () use ($iterator, $length, $preserve_keys): Generator {
            for ($i = 0; $i < $length && $iterator->valid(); ++$i, $iterator->next()) {
                if (true === $preserve_keys) {
                    yield $iterator->key() => $iterator->current();
                } else {
                    yield $iterator->current();
                }
            }
        };
        while ($iterator->valid()) {
            yield $chunk();
        }
    }
}
