<?php

namespace App\Mail\ClaimRegistered;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class External extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Claim
     */
    private $claim;

    /**
     * Create a new message instance.
     *
     * @param Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('boardingclaims.com -- claim number ' . $this->claim->file_number)
                    ->view('emails.claims.registered-external', [
                        'base_url' => config('app.frontend_url'),
                        'file_number' => $this->claim->file_number,
                        'last_name' => $this->claim->complainant->last_name,
                        'salutation_code' => $this->claim->complainant->salutation->translation_code,
                        'isMale' => ($this->claim->complainant->salutation->id === 1),
                        'mail_from_address' => 'info@boardingclaims.com',
                        'mail_from_name' => config('mail.from.name'),
                    ]);
    }
}
