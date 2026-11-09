<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('simulation_scenarios', function (Blueprint $table) {
            // Foreign Key ke modules (nullable dulu agar data seeder lama tidak error)
            $table->foreignId('module_id')->nullable()->after('id')->constrained('modules')->onDelete('set null');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->after('difficulty');
            $table->integer('sort_order')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('simulation_scenarios', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id', 'status', 'sort_order']);
        });
    }
};
