<?php
// ==========================================================
// 2024_01_01_000001_create_portfolios_table.php
// ==========================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('client')->nullable();
            $table->text('description')->nullable();
            $table->string('project_url')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('tags')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('portfolios'); }
};
