<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Used by start-container.sh on every deploy: seeds roles, staff, settings and
// the demo catalogue on the first deploy only, never on top of existing data.
Artisan::command('store:seed-if-empty', function () {
    if (User::exists()) {
        $this->info('Database already has data, skipping the demo seed.');

        return;
    }

    $this->info('Empty database, seeding demo store…');
    $this->call('db:seed', ['--force' => true]);
})->purpose('Seed the demo store on first deploy only');
