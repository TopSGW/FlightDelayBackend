<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org=/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/199=9/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width"/>

    <title>BoardingClaims - Claim registered</title>

    <!-- Styles -->
    <style>
    </style>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Boarding Claims</th>
    </tr>
    <tr>
        <th>Contact request received.</th>
    </tr>
    </thead>
    <body>
    <tr>
        <td>
            <dl>
                <dt>Naam</dt>
                <dd>{{ $contact->name }}</dd>

                <dt>Email</dt>
                <dd>{{ $contact->email }}</dd>

                <dt>Onderwerp</dt>
                <dd>{{ $contact->subject }}</dd>

                <dt>Bericht</dt>
                <dd>{{ $contact->message }}</dd>
            </dl>
        </td>
    </tr>
    </body>
</table>
</body>
</html>
