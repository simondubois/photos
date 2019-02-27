<?php

namespace App\Providers;

use App\Collections\PhotoCollection;
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
        $this->app->singleton(PhotoCollection::class, function () {
            return with(new PhotoCollection())->fromPath(env('PHOTOS_ROOT'));
        });
    }
}
