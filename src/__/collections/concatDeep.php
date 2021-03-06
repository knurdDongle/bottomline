<?php

namespace collections;

/**
 * Recursively combines and concat collections provided with each others.
 *
 * If the collections have common keys, then the values are appended in an array.
 * If numerical indexes are passed, then values are appended.
 *
 * For a non-recursive concat, see `__::concat()`.
 *
 * **Usage**
 *
 * ```php
 * __::concatDeep(
 *     ['color' => ['favorite' => 'red', 5], 3],
 *     [10, 'color' => ['favorite' => 'green', 'blue']]
 * );
 * ```
 *
 * **Result**
 *
 * ```
 * [
 *     'color' => [
 *         'favorite' => ['red', 'green'],
 *         5,
 *         'blue'
 *     ],
 *     3,
 *     10
 * ]
 * ```
 *
 * @param array|object $collection First collection to concatDeep.
 * @param array|object ...$_        N other collections to concatDeep.
 *
 * @return array|object Concatenated collection.
 */
function concatDeep()
{
    return \__::reduceRight(func_get_args(), function ($source, $result) {
        \__::doForEach($source, function ($sourceValue, $key) use (&$result) {
            if (!\__::has($result, $key)) {
                $result = \__::set($result, $key, $sourceValue);
            } elseif (is_numeric($key)) {
                $result = \__::concat($result, [$sourceValue]);
            } else {
                $resultValue = \__::get($result, $key);
                $result = \__::set($result, $key, concatDeep(
                    \__::isCollection($resultValue) ? $resultValue : (array) $resultValue,
                    \__::isCollection($sourceValue) ? $sourceValue : (array) $sourceValue
                ));
            }
        });
        return $result;
    }, []);
}
