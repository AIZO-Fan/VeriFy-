<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('simulation_attempts', function (Blueprint $table) {
            $table->id();
            // user_id dibuat nullable karena user bisa main tanpa login (Guest)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('scenario_id')->constrained('simulation_scenarios')->onDelete('cascade');

            // Menyimpan riwayat klik/keputusan user dari awal sampai akhir dalam bentuk JSON array
            $table->json('decision_path');

            $table->integer('score');
            $table->integer('max_score');
            $table->unsignedInteger('scenario_version');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulation_attempts');
    }
};
