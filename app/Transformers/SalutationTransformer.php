<?php

namespace App\Transformers;

class SalutationTransformer extends Transformer
{
    /**
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['id'],
            'translation_code' => $item['translation_code'],
            'name' => $item['name'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}