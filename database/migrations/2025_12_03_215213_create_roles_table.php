<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Role ID
            $table->string('name')->unique(); // e.g., 'admin', 'student', 'teacher'
            $table->string('description')->nullable(); // optional description of role
            $table->json('permissions')->nullable(); // optional JSON to store permissions
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
