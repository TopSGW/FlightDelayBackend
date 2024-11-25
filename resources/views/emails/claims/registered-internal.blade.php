<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org=/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/199=9/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width"/>

    <title>BoardingClaims - Claim registered</title>
</head>
<body>

<table style="width: 500px; border-spacing: 0;">
    <thead>
    <tr style="background:#055087; color: white;">
        <th colspan="2" style="padding: 0; height: 75px;">
            Nieuwe claim
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2"><br></td>
    </tr>
    <tr>
        <td colspan="2">
            <h1>Claim gegevens</h1>
        </td>
    </tr>
    <tr>
        <td style="width: 50px;"></td>
        <td style="width: 400px">
            <table style="width: 100%; text-align: left;">
                <tbody>
                <tr>
                    <th>Claim nummer</th>
                    <td>{{ $claim->file_number }}</td>
                </tr>
                <tr>
                    <th>Type claim</th>
                    <td>{{ __($claim->claimType->translation_code) }}</td>
                </tr>
                <tr>
                    <th>Opmerkingen</th>
                    <td>{{ $claim->remarks }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><br></td>
    </tr>
    <tr>
        <td colspan="2">
            <h1>Klant gegevens</h1>
        </td>
    </tr>
    <tr>
        <td style="width: 50px;"></td>
        <td>
            <table style="width: 100%; text-align: left;">
                <tbody>
                <tr>
                    <th>Aanspreektitel</th>
                    <td>{{ __($complainant->salutation->translation_code) }}</td>
                </tr>
                <tr>
                    <th>Voornaam</th>
                    <td>{{ $complainant->first_name }}</td>
                </tr>
                <tr>
                    <th>Achternaam</th>
                    <td>{{ $complainant->last_name }}</td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>{{ $complainant->country ? $complainant->country->name : '' }}</td>
                </tr>
                <tr>
                    <th>Taal</th>
                    <td>{{ strtoupper($complainant->language) }}</td>
                </tr>
                <tr>
                    <th>Straat</th>
                    <td>{{ $complainant->street }}</td>
                </tr>
                <tr>
                    <th>Huisnummer</th>
                    <td>{{ $complainant->house_number }}</td>
                </tr>
                <tr>
                    <th>Busnummer</th>
                    <td>{{ $complainant->box_number }}</td>
                </tr>
                <tr>
                    <th>Postcode</th>
                    <td>{{ $complainant->postal_code }}</td>
                </tr>
                <tr>
                    <th>Gemeente</th>
                    <td>{{ $complainant->city }}</td>
                </tr>
                <tr>
                    <th>E-mail adres</th>
                    <td>{{ $complainant->email }}</td>
                </tr>
                <tr>
                    <th>Telefoonnummer</th>
                    <td>{{ $complainant->phone_number }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><br></td>
    </tr>
    <tr>
        <td colspan="2">
            <h1>Vluchtgegevens</h1>
        </td>
    </tr>
    @foreach($flights as $flight)
        <tr>
            <td colspan="2">
                <h2>
                    @if($flight->is_initial_flight)
                        Initiële vlucht
                    @else
                        Aansluitende vlucht
                    @endif
                </h2>
            </td>
        </tr>
        <tr>
            <td style="width: 50px;"></td>
            <td>
                <table style="width: 100%; text-align: left;">
                    <tbody>
                    <tr>
                        <th>Vluchtdatum</th>
                        <td>{{ $flight->flight_date }}</td>
                    </tr>
                    @if($flight->flight_number !== null)
                        <tr>
                            <th>Vluchtnummer</th>
                            <td>{{ $flight->flight_number }}</td>
                        </tr>
                    @else
                        <tr>
                            <th>Vertrek</th>
                            <td>{{ $flight->departureAirport->name or '(geen opgegeven)' }}</td>
                        </tr>
                        <tr>
                            <th>Aankomst</th>
                            <td>{{ $flight->destinationAirport->name  or '(geen opgegeven)' }}</td>
                        </tr>
                        <tr>
                            <th>Luchtvaartmaatschappij</th>
                            <td>{{ $flight->airline->name or '(geen opgegeven)' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Vertraging</th>
                        <td>{{ __($flight->delay->translation_code) }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="background:#055087; color: white;">
        <td colspan="2" style="text-align: left;height: 32px"></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
