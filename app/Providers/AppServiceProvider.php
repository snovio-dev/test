<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use App\Services\HttpService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('email_domain', function ($attribute, $value, $parameters, $validator) {
            return (new HttpService())->isValidEmailDomain($value);
        }, 'The :domain of the email is banned.');
    }
}
