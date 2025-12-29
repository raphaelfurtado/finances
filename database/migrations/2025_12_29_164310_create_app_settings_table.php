<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('app_settings')->insert([
            ['key' => 'maintenance_mode', 'value' => 'false', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_message', 'value' => 'Estamos em manutenção. Voltaremos em breve!', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'global_banner_active', 'value' => 'false', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'global_banner_message', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'global_banner_type', 'value' => 'info', 'created_at' => now(), 'updated_at' => now()], // info, warning, error, success
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
