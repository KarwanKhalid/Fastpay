<?php

namespace KarwanKhalid\Fastpay;

use Illuminate\Support\ServiceProvider;
use KarwanKhalid\Fastpay\Services\Fastpay;

class FastpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFastpay();
    }

    /**
     * Register Fastpay.
     *
     * @return void
     */
    protected function registerFastpay()
    {
        $this->app->bind('fastpay', function () {
            return new Fastpay();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootFastpay();
    }

    /**
     * Bootstrap Fastpay.
     *
     * @return void
     */
    protected function bootFastpay()
    {
        $this->publishes([
            __DIR__.'/config/fastpay.php' => config_path('faspay.php'),
            __DIR__.'/resources/views' => $this->app->resourcePath('views/vendor/KarwanKhalid/Fastpay'),
            __DIR__.'/database/migrations' => $this->app->databasePath('/migrations')
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/config/fastpay.php', 'fastpay'
        );
        $this->loadRoutesFrom(
            __DIR__.'/routes/fastpayroutes.php'
        );
        $this->app['router']->middleware('web', 'KarwanKhalid\Fastpay\Http\Middleware\VerifyCsrfToken::class');
    }

    /**
     * Get the service provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'fastpay'
        ];
    }
}
