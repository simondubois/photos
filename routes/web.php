<?php

use App\Collections\PhotoCollection;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function (PhotoCollection $photos) {
    return view('home', [
        'minDate' => $photos->min('date') ?? Carbon::today(),
        'maxDate' => min($photos->max('date'), Carbon::today()) ?? Carbon::today(),
    ]);
});

$router->get('feed', function (PhotoCollection $photos) {
    return $photos->toRss();
});

$router->get('random', function (PhotoCollection $photos) {
    return $photos;
});

$router->get('{year}/{month}', function (PhotoCollection $photos, $year, $month) {
    return $photos
        ->where('year', $year)
        ->where('month', $month);
});

$router->get('{year}/{month}/{day}', function (PhotoCollection $photos, $year, $month, $day) {
    return $photos
        ->where('day', $day)
        ->where('year', $year)
        ->where('month', $month);
});

$router->get('seed', function () {
    if (env('APP_ENV') !== 'demo') {
        abort(404);
    }
    app(Kernel::class)->call('photo:seed');
    return app(Kernel::class)->output();
});
