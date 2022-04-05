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

Route::view('/', 'welcome');

Route::get('/update', function () {
    $commands = [
        'git pull' => 'Git',
        'php artisan optimize:clear' => 'Cache',
        'npm run prod' => 'Build',
        'php artisan optimize:clear' => 'Cache',
    ];

    foreach ($commands as $command => $prefix) {
        $basePath = base_path();
        echo "<div style='font-weight:bold'>{$prefix}:</div>";
        echo "<div style='font-style:italic'><small>Please wait...</small></div>";
        ob_flush();
        $cmd = "cd {$basePath} && {$command}";
        $output = shell_exec($cmd);
        echo nl2br($output) . '<br>';
        ob_flush();
    }
});

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
