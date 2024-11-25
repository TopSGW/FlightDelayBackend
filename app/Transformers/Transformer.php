<?php

namespace App\Transformers;

abstract class Transformer
{
    /**
     * @param array $items
     * @return array
     */
    public function transformCollection(Array $items): array
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * @param array $item
     * @return mixed
     */
    public abstract function transform(array $item): array;
}