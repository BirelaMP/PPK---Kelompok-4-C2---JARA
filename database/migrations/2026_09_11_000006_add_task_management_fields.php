<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('priority')->default('medium')->after('description');
            $table->date('deadline')->nullable()->after('priority');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['description', 'priority', 'deadline']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
