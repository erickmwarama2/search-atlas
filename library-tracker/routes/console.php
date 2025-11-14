<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('overdue_loan_notification', function() {
    DB::table('loans')->where('due_date', '<', time())->get();
})->purpose('nightly overdue loans notification')->dailyAt('20:00');
