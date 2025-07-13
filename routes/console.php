<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('app:process-scheduled-deletions')->daily();
