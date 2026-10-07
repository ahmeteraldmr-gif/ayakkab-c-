<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('auth:clear-resets')->daily();
\Illuminate\Support\Facades\Schedule::command('queue:prune-failed --hours=48')->daily();

