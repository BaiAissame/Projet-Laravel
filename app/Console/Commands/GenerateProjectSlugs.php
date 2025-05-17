<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateProjectSlugs extends Command
{
    protected $signature = 'projects:generate-slugs';
    protected $description = 'Génère des slugs pour tous les projets existants';

    public function handle()
    {
        $projects = Project::whereNull('slug')->orWhere('slug', '')->get();
        $count = 0;

        foreach ($projects as $project) {
            $baseSlug = Str::slug($project->name);
            $slug = $baseSlug;
            $counter = 1;

            // Vérifie si le slug existe déjà
            while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $project->slug = $slug;
            $project->save();
            $count++;
        }

        $this->info("$count projets ont été mis à jour avec des slugs.");
    }
}