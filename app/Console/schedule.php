<?php
use Illuminate\Console\Scheduling\Schedule;
return function (Schedule $schedule) {
    $schedule->command('invitation:resend-pending --days=3')->everyMinute();
};