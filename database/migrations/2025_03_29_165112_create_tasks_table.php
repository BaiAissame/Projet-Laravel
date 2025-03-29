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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de la tâche
            $table->text('description')->nullable(); // Description de la tâche
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Projet associé
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Assigné à un utilisateur
            $table->foreignId('category_id')->nullable()->constrained('task_categories')->onDelete('set null'); // Catégorie de tâche
            $table->foreignId('priority_id')->nullable()->constrained('task_priorities')->onDelete('set null'); // Priorité de la tâche
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo'); // Statut de la tâche
            $table->date('due_date')->nullable(); // Date limite
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
