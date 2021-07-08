<?php

namespace KarwanKhalid\Fastpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FastpayFacade
 * @package KarwanKhalid\Fastpay
 */
class FastpayFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fastpay';
    }
}