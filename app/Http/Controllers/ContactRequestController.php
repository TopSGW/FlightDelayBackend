<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactRequested;
use Illuminate\Support\Facades\Validator;use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class ContactRequestController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateClaimRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $contactInput = Input::only(['name', 'email', 'subject', 'message']);
        $validator = Validator::make($contactInput, (new ContactRequest())->rules());

        if ($validator->fails()) {
            return $this->respondValidationFailed('Parameters failed validation for a contact request.', 'contact');
        }

        $contact = new Contact($contactInput);

        Mail::to(config('mail.from.address'))->send(new ContactRequested($contact));

        return $this->respondCreated('Contact request successfully created');
    }
}
