<?php

namespace App\Transformers;

class CountryTransformer extends Transformer
{
    /**
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['id'],
            'iso_code' => $item['iso_code'],
            'name' => $item['name'],
            'continent_id' => $item['continent_id'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}