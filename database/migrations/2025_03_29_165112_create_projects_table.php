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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du projet
            $table->text('description')->nullable(); // Description du projet
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Créateur du projet
            $table->date('start_date')->nullable(); // Date de début
            $table->date('end_date')->nullable(); // Date de fin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
