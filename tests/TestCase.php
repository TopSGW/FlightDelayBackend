<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function collectionsAreEqual($expectedCollection, $actualCollection) {
        $this->assertInstanceOf(Collection::class, $expectedCollection);
        $this->assertInstanceOf(Collection::class, $actualCollection);

        $this->assertEquals($expectedCollection->count(), $actualCollection->count());

        $expectedCollection->each(function($expected, $key) use($actualCollection) {
            $exists = $actualCollection->search(function($actual, $key) use($expected) {
                $equals = true;

                foreach ($expected->toArray() as $field => $value) {
                    $equals = $equals && (array_key_exists($field, $actual)) && ($value == $actual[$field]);
                }

                return $equals;
            });

            $this->assertFalse(($exists === false));
        });
    }
}
