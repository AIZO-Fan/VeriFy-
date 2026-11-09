<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('simulation_scenarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('topic_id')->comment('ID array statis 1-6');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('version')->default(1);
            $table->json('story');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulation_scenarios');
    }
};
