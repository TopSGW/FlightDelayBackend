<?php

namespace App\Http\Controllers;

use App\Claim;
use App\Http\Requests\Export\ClaimRequest;
use App\Transformers\Export\ClaimTransformer;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ExportController extends ApiController
{
    const FILE_NAME = 'claims_export';

    /**
     * @var ClaimTransformer
     */
    protected $claimTransformer;

    /**
     * AirlinesForFlightRouteController constructor.
     * @param ClaimTransformer $claimTransformer
     */
    public function __construct(ClaimTransformer $claimTransformer)
    {
        $this->claimTransformer = $claimTransformer;
    }

    public function claims(ClaimRequest $request)
    {
        $key = $request->get('key');
        $from = $request->get('from', (new Carbon)->subMonth(1));
        $until = $request->get('until', new Carbon());

        if ($key === null || $key !== config('api.authentication_key')) {
            return $this->setStatusCode(SymfonyResponse::HTTP_FORBIDDEN)
                ->respondWithError('not authorized');
        }

        if ($from instanceof Carbon == false) {
            $from = Carbon::createFromFormat('Y-m-d', $from);
        }

        if ($until instanceof Carbon === false) {
            $until = Carbon::createFromFormat('Y-m-d', $until);
        }

        if (is_null($until) || $until === '') {
            $until = new Carbon();
        }

        $claims = (new Claim)->betweenWithDetails($from, $until);

        $claims = $this->claimTransformer->transformCollection($claims->toArray());

        return $this->createExcelFile($claims, $from, $until)->download('xlsx');
    }

    protected function createExcelFile($claims, Carbon $from, Carbon $until)
    {
        $from = $from->toDateString();
        $until = $until->toDateString();

        $fileName = implode('_', [self::FILE_NAME, $from, $until]);

        return Excel::create($fileName, function ($excel) use ($claims, $from, $until) {
            $sheetName = 'Overzicht ontvangen claims';

            $excel->sheet($sheetName, function ($sheet) use ($claims) {
                $sorted = array_map(function($claim) {
                    return [
                        'Referentie' => $claim['Referentie'],
                        'Datum en uur van indiening' => $claim['Datum en uur van indiening'],
                        'Aanspreking' => $claim['Aanspreking'],
                        'Voornaam' => $claim['Voornaam'],
                        'Achternaam' => $claim['Achternaam'],
                        'Land' => $claim['Land'],
                        'Taal' => $claim['Taal'],
                        'Postcode' => $claim['Postcode'],
                        'Gemeente' => $claim['Gemeente'],
                        'Straat' => $claim['Straat'],
                        'Nr.' => $claim['Nr.'],
                        'PO' => $claim['PO'],
                        'E-mailadres' => $claim['E-mailadres'],
                        'Telefoonnummer' => $claim['Telefoonnummer'],
                        'Type claim' => $claim['Type claim'],
                        'Vertraging' => $claim['Vertraging'],
                        'Vluchtnummer' => $claim['Vluchtnummer'],
                        'Vluchtdatum' => $claim['Vluchtdatum'],
                        'Maatschappij' => $claim['Maatschappij'],
                        'Vertrek' => $claim['Vertrek'],
                        'Eindbestemming' => $claim['Eindbestemming'],
                    ];
                }, $claims);

                $sheet->setOrientation('landscape')->fromArray($sorted);
            });

            $sheetName = 'Opmerkingen klant';

            $excel->sheet($sheetName, function ($sheet) use ($claims) {
                $comments = array_map(function ($claim) {
                    return [
                        'Referentie' => $claim['Referentie'],
                        'Datum en uur van indiening' => $claim['Datum en uur van indiening'],
                        'Aanspreking' => $claim['Aanspreking'],
                        'Voornaam' => $claim['Voornaam'],
                        'Achternaam' => $claim['Achternaam'],
                        'Opmerkingen klant' => $claim['Opmerkingen klant'],
                    ];
                }, $claims);

                $sheet->setOrientation('landscape')->fromArray($comments);
            });

            $sheetName = 'Input keuzemenu\'s';

            $excel->sheet($sheetName, function ($sheet) use ($claims) {
                $sheet->setOrientation('landscape')->fromArray([
                    [
                        'Connectie' => 'Ja',
                        'Ontvankelijk' => 'Ja',
                        'Overmacht' => 'Ja',
                        'Status' => 'Afgesloten wegens overmacht',
                        'Klant uitbetaald' => 'Ja',
                        'Claim succesvol' => 'Ja',
                        'Vluchtinfo' => 'Ja',
                    ],
                    [
                        'Connectie' => 'Nee',
                        'Ontvankelijk' => 'Nee',
                        'Overmacht' => 'Nee',
                        'Status' => 'Verduidelijking gevraagd aan klant',
                        'Klant uitbetaald' => 'Nee',
                        'Claim succesvol' => 'Nee',
                        'Vluchtinfo' => 'Nee',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => 'Niet zeker',
                        'Status' => 'Brief maatschappij verstuurd',
                        'Klant uitbetaald' => 'NVT',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'Herinnering maatschappij verstuurd',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'In onderhandeling',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'Rechtzaak tegen maatschappij',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'Afgesloten na uitbetaling',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'Klant ingegaan op aanbod maatschappij',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                    [
                        'Connectie' => '',
                        'Ontvankelijk' => '',
                        'Overmacht' => '',
                        'Status' => 'Rechtzaak tegen klant',
                        'Klant uitbetaald' => '',
                        'Claim succesvol' => '',
                        'Vluchtinfo' => '',
                    ],
                ]);
            });
        });
    }


}
