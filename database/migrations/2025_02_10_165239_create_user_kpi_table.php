<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_kpi', function (Blueprint $table) {
            $table->id();
            $table->string('actual');
            $table->double('review');
            $table->foreignId('user_id')
            ->nullable(false)
            ->constrained('users', 'id')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('kpi_id')
            ->constrained('kpis', 'id')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kpi');
    }
};
