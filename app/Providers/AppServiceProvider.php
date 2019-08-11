<?php

namespace App\Providers;

use App\Collections\PhotoCollection;
use Carbon\Carbon;
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
        Carbon::setLocale(env('APP_LOCALE'));
        setlocale(LC_TIME, env('APP_LOCALE') . '_' . strtoupper(env('APP_LOCALE')));

        $this->app->singleton(PhotoCollection::class, function () {
            return with(new PhotoCollection())->fromPath(env('PHOTOS_ROOT'));
        });
    }
}
