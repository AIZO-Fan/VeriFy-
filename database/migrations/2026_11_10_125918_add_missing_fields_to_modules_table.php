<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (! Schema::hasColumn('modules', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])
                    ->default('draft')
                    ->after('description');
            }

            if (! Schema::hasColumn('modules', 'sort_order')) {
                $table->integer('sort_order')
                    ->default(0)
                    ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'sort_order')) {
                $table->dropColumn('sort_order');
            }

            if (Schema::hasColumn('modules', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};