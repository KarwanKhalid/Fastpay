<?php

Route::get('/example', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@exampleCheckout');

Route::post('/pay', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@index');

Route::post('/success', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@success');
Route::post('/fail', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@fail');
Route::post('/cancel', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@cancel');

Route::post('/ipn', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@ipn');

Route::get('/dump', 'KarwanKhalid\Fastpay\Http\Controllers\FastpayPaymentController@dump');