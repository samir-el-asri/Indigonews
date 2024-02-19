<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Search\All_index;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
// Import Builder where defaultStringLength method is defined

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
        //Schema::defaultStringLength(255);
        Builder::defaultStringLength(191);
        // Update defaultStringLength
        all_index::bootSearchable();
    }
}
