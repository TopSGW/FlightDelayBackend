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
        <th colspan="1" style="padding: 0;">
            <img src="{{ $base_url }}/static/images/site_logo.png" style="width: 122px;">
        </th>
        <th colspan="2" style="padding: 0;">
            <img src="{{ $base_url }}/static/images/carousel/carousel-1-sm.jpg" style="width: 100%; height: auto;">
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 50px;">
            &nbsp;
        </td>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>
                        @if($isMale === true)
                            {{ __('mail-claim-registered.greeting_male') }}
                        @else
                            {{ __('mail-claim-registered.greeting_female') }}
                        @endif
                        {{ __($salutation_code) }} {{ $last_name }},
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td>
                        {!! nl2br(__('mail-claim-registered.introduction', [
                            'file_number' => $file_number,
                            'mail_to' => '<a href="mailto:' . $mail_from_address . '">' . $mail_from_name . '</a>',
                            'faq_url' => '<a href="' . $base_url . '/#/faq">FAQ</a>'
                            ])) !!}
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td>{{ __('mail-claim-registered.closing') }}</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td>Team Boarding Claims</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                </tbody>
            </table>
        </td>
        <td style="width: 50px;"> &nbsp;
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr style="background:#055087; color: white;">
        <th colspan="3" style="text-align: left;">
            <img src="{{ $base_url }}/static/images/footer_logo.png" style="width: 122px; height: auto;">
        </th>
    </tr>
    </tfoot>
</table>
</body>
</html>
