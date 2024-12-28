<?php

namespace App\Providers;

use App\Models\CompanyInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use View;
use Illuminate\Support\Facades\URL;

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
        Schema::defaultStringLength(191);

        // $companydata = CompanyInfo::first();

        // View::share('company_info_share', $companydata);
        // if ($this->app->environment('production')) {
        // if ($this->app->environment('local')) {
        //     URL::forceScheme('https');
        // }
    }
}
