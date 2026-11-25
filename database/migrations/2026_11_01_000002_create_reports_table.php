<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 30)->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('reporter_name', 100)->nullable();
            $table->string('reporter_class', 50)->nullable();
            $table->string('reporter_contact', 100)->nullable();
            $table->string('location', 200);
            $table->string('title', 150);
            $table->text('description');
            $table->string('priority', 20)->default('sedang');
            $table->string('status', 30)->default('menunggu');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('verified_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
