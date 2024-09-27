<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceChanged extends Mailable
{
    public $subscription;
    public $newPrice;

    public function __construct($subscription, $newPrice)
    {
        $this->subscription = $subscription;
        $this->newPrice = $newPrice;
    }

    public function build()
    {
        return $this->view('emails.price_changed')
                    ->with([
                        'adUrl' => $this->subscription->ad_url,
                        'oldPrice' => $this->subscription->current_price,
                        'newPrice' => $this->newPrice,
                    ]);
    }
}

