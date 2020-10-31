<?php

namespace App\Providers;

use App\Repositories\Contracts\ChargingStationsRepository;
use App\Repositories\EloquentChargingStationsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChargingStationsRepository::class, EloquentChargingStationsRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
