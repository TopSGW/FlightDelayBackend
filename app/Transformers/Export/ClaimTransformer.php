<?php

namespace App\Transformers\Export;

use App\Transformers\Transformer;

class ClaimTransformer extends Transformer
{
    /**
     * @param array $claim
     * @return array
     */
    public function transform(array $claim): array
    {
        return array_merge(
            $this->transformClaim($claim),
            $this->transformComplainant($claim['complainant']),
            $this->transformInitialFlight($claim['flights'])
        );
    }

    protected function transformClaim(array $claim)
    {
        return [
            // The claim itself
            'Referentie' => $claim['file_number'],
            'Datum en uur van indiening' => $claim['created_at'],
            'Type claim' => $claim['claim_type']['name'],
            'Opmerkingen klant' => $claim['remarks'],
        ];
    }

    protected function transformComplainant(array $complainant)
    {
        return [
            // The complainant
            'Aanspreking' => $complainant['salutation']['name'],
            'Voornaam' => $complainant['first_name'],
            'Achternaam' => $complainant['last_name'],
            'Land' => $complainant['country']['name'],
            'Taal' => $complainant['language'],
            'Postcode' => $complainant['postal_code'],
            'Gemeente' => $complainant['city'],
            'Straat' => $complainant['street'],
            'Nr.' => $complainant['house_number'],
            'PO' => $complainant['box_number'],
            'E-mailadres' => $complainant['email'],
            'Telefoonnummer' => $complainant['phone_number'],
        ];
    }

    protected function transformInitialFlight(array $flights)
    {
        return $this->transformFlight($flights[0], true);
    }

    protected function transformConnectingFlight(array $flights)
    {
        return $this->transformFlight(isset($flights[1]) ? $flights[1] : null, false);
    }

    protected function transformFlight($flight, $isInitial = true)
    {
        $type = $isInitial === true ? 'initial' : 'connecting';

        if ($type !== 'initial') {
            return [];
        }

        if ($flight === null) {
            return [
                'Vertraging' => null,
                'Vluchtnummer' => null,
                'Vluchtdatum' => null,
                'Maatschappij' => null,
                'Vertrek' => null,
                'Eindbestemming' => null,
            ];
        }

        return [
            'Vertraging' => $flight['delay']['name'],
            'Vluchtnummer' => $flight['flight_number'],
            'Vluchtdatum' => $flight['flight_date'],
            'Maatschappij' => $flight['airline_id'] === null
                ? null
                : $flight['airline']['name'],
            'Vertrek' => $flight['departure_airport_id'] === null
                ? null
                : $flight['departure_airport']['name'],
            'Eindbestemming' => $flight['destination_airport_id'] === null
                ? null
                : $flight['destination_airport']['name'],
        ];
    }
}