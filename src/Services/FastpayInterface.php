<?php

namespace KarwanKhalid\Fastpay\Services;

interface FastpayInterface
{
    public function makePayment(array $data);

    public function orderValidate($trxID, $amount, $requestData);

    public function setParams($data);

    public function setRequiredInfo(array $data);

    public function callToApi($data, $setLocalhost = false);
}