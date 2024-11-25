<?php

namespace App\Mail\ClaimRegistered;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Internal extends Mailable
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
        return $this->subject('Nieuwe claim -- claim nummer ' . $this->claim->file_number)
                    ->view('emails.claims.registered-internal', [
                        'claim' => $this->claim,
                        'complainant' => $this->claim->complainant,
                        'flights' => $this->claim->flights,
                    ]);
    }
}
