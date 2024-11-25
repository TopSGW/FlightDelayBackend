<?php

namespace App\Transformers;

class AirlineTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'iata_code' => $item['iata_code'],
            'icao_code' => $item['icao_code'],
            'call_sign' => $item['call_sign'],
            'country_id' => (int)$item['country_id'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}