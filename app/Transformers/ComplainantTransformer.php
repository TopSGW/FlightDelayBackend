<?php

namespace App\Transformers;

class ComplainantTransformer extends Transformer
{
    /**
     * @param array $complainant
     * @return array
     */
    public function transform(array $complainant): array
    {
        return [
            'id' => $complainant['id'],
            'claim_id' => (int)$complainant['claim_id'],
            'country_id' => (int)$complainant['country_id'],
            'salutation_id' => (int)$complainant['salutation_id'],
            'first_name' => $complainant['first_name'],
            'last_name' => $complainant['last_name'],
            'postal_code' => $complainant['postal_code'],
            'city' => $complainant['city'],
            'street' => $complainant['street'],
            'house_number' => $complainant['house_number'],
            'box_number' => $complainant['box_number'],
            'email' => $complainant['email'],
            'phone_number' => $complainant['phone_number'],
            'remarks' => $complainant['remarks'],
            'created_at' => $complainant['created_at'],
            'updated_at' => $complainant['updated_at'],
        ];
    }
}