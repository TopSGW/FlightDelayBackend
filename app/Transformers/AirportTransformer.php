<?php

namespace App\Transformers;

class AirportTransformer extends Transformer
{
    /**
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['id'],
            'identification' => $item['identification'],
            'type' => $item['type'],
            'name' => $item['name'],
            'latitude' => $item['latitude'],
            'longitude' => $item['longitude'],
            'continent_id' => $item['continent_id'],
            'country_id' => $item['country_id'],
            'region_id' => $item['region_id'],
            'municipality' => $item['municipality'],
            'gps_code' => $item['gps_code'],
            'iata_code' => $item['iata_code'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}