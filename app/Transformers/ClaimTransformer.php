<?php

namespace App\Transformers;

class ClaimTransformer extends Transformer
{
    /**
     * @param array $claim
     * @return array
     */
    public function transform(array $claim): array
    {
        return [
            'id' => $claim['id'],
            'claim_type_id' => (int)$claim['claim_type_id'],
            'remarks' => $claim['remarks'],
            'created_at' => $claim['created_at'],
            'updated_at' => $claim['updated_at'],
        ];
    }
}