<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setDatabaseStringLength();
    }

    /**
     * @return void
     */
    private function setDatabaseStringLength(): void
    {
        Schema::defaultStringLength(191);
    }
}
