<?php

namespace App\Http\Controllers;

use App\Claim;
use App\Contact;
use App\Mail\ClaimRegistered\External;
use App\Mail\ClaimRegistered\Internal;
use App\Mail\ContactRequested;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendClaimRegisteredExternalMail()
    {
        $claims = Claim::with('claimType',
            'complainant.country',
            'complainant.salutation',
            'flights.departureAirport',
            'flights.destinationAirport',
            'flights.airline',
            'flights.delay')->get()->take(5);

        foreach ($claims as $claim) {
            App::setLocale($claim->complainant->language);

            Mail::to($claim->complainant->email)->send(new External($claim));
        }
    }

    public function sendClaimRegisteredInternalMail()
    {
        $claims = Claim::with(
            'claimType',
            'complainant.country',
            'complainant.salutation',
            'flights.departureAirport',
            'flights.destinationAirport',
            'flights.airline',
            'flights.delay')->get();

        foreach ($claims as $claim) {
            App::setLocale('nl');

            Mail::to(config('mail.from.address'))->send(new Internal($claim));
        }
    }

    public function sendContactRequestMail()
    {
        $contact = new Contact([
            'name' => 'Jullie Andrews',
            'email' => 'j.a@nowhere.com',
            'subject' => 'This is the subject',
            'message' => 'This is the message for the contact request',
        ]);

        Mail::to($contact->email)->send(new ContactRequested($contact));
    }
}
