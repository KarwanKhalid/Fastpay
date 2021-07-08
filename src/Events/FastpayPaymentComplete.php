<?php

namespace KarwanKhalid\Fastpay\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class FastpayPaymentComplete
{
    use Dispatchable, SerializesModels;

    public $paymentdata;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($paymentdata)
    {
        $this->paymentdata = $paymentdata;
    }

}
