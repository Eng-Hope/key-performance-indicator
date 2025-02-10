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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('measurement')->nullable(false);
            $table->string('review_duration')->nullable(false);
            $table->string('target')->nullable(false);
            $table->double('weight')->nullable(false);

            $table->foreignId('created_by')
            ->nullable(false)
            ->constrained('users', 'id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
