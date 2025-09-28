<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users');
            $table->string('mode')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appearances');
    }
};
