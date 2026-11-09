<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OLD_URL = 'https://instagram.com/crochetstore';
    private const NEW_URL = 'https://www.instagram.com/_sis_trella/';

    public function up(): void
    {
        DB::table('settings')
            ->where('key', 'instagram_url')
            ->where('value', self::OLD_URL)
            ->update(['value' => self::NEW_URL]);
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'instagram_url')
            ->where('value', self::NEW_URL)
            ->update(['value' => self::OLD_URL]);
    }
};
