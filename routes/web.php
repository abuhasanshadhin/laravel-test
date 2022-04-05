<?php

use App\Events\PodcastProcessed;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\TestController;
use Symfony\Component\Process\Exception\ProcessFailedException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $o = shell_exec('npm i');
    echo nl2br($o);
    flush();
    ob_flush();
    $o1 = shell_exec('npm run prod');
    echo nl2br($o1);
    flush();
    ob_flush();
});

// Route::view('/', 'welcome');

// Route::get('/singleton', function () {
//     App::singleton('time', function ($app) {
//         return date('Y-m-d h:i:s A');
//     });

//     return $this->app->make('time');
// });

// Route::post('/auth-check', function () {
//     return true;
// });

Route::resource('test', TestController::class);
