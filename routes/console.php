<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('event:send-reminder', function (Schedule $schedule) {
    $schedule->command('event:send-reminder')
        ->everyMinute() // Menjalankan setiap menit
        ->onOneServer(); // Pastikan hanya berjalan di satu server jika menggunakan beberapa server
})->purpose('Menjalankan task scheduler untuk mengirim reminder event.');