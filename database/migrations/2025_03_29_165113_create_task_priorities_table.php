<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nom de la priorité (ex: "Haute", "Moyenne", "Basse")
            $table->string('color')->nullable(); // Code couleur optionnel (ex: rouge, orange, vert)
            $table->integer('level')->unique(); // Niveau de priorité (ex: 1=Haute, 2=Moyenne, 3=Basse)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_priorities');
    }
};
