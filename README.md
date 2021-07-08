# KarwanKhalid\Fastpay

Laravel Package for the Fastpay Payment Gateway API

## Installation

You will need [composer](https://getcomposer.org/) to install Fastpay. Then publish assets and migrate the table for payment records.

```bash
composer require "KarwanKhalid/Fastpay" --no-cache
php artisan vendor:publish
php artisan migrate
```

I haven't tested it below Laravel 5.5 . If you wanna try in below Laravel 5.5 , you will need to edit **config/app.php** and add the following line in the providers section.

```php
KarwanKhalid\Fastpay\FastpayServiceProvider::class
```

Add the following constants in the **.env** file of your Laravel Project.

```bash
API_DOMAIN_URL=https://dev.fast-pay.cash
MERCHANT_MOBILE_NO=PUT_YOUR_SANDBOX_MERCHANT_MOBILE_NO
STORE_PASSWORD=PUT_YOUR_SANDBOX_STORE_PASSWORD
IS_LOCALHOST=true
```

Clear config cache if required.

```bash
php artisan config:cache
```

That's it! Enjoy!

## Usage

* Go to *http://yourdomain/example* for testing the Payment  from Fastpay site.

For listening to the payment event, open your **app/Providers** directory and add your own listener for the **FastpayPaymentComplete** event class.

## Changelog

### 1.0.0-alpha
* Initial Release


## Note
* I don't have any live Fastpay store. So could not test that. Please open an issue if you face any problem with **LIVE** payments so I can test with you and fix that.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://opensource.org/licenses/MIT)
