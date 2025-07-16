<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $results = null;
        if (!empty($request->input('search'))) {
            $results = Project::where('user_id', Auth::user()->id)
                ->where('name', 'like', '%' . $request->input('search') . '%')
                ->select('id', 'name', 'slug', 'description')
                ->get();
        }

        // Projets créés par l'utilisateur - optimisé
        $projets = Project::where('user_id', Auth::user()->id)
            ->select('id', 'name', 'slug', 'description', 'created_at')
            ->get();

        // Projets partagés avec l'utilisateur (où il est membre) - optimisé
        $sharedProjects = Auth::user()->sharedProjects()
            ->select('projects.id', 'projects.name', 'projects.slug', 'projects.description', 'projects.created_at')
            ->get();
            
        return view('dashboard', compact('projets', 'sharedProjects', 'results'));
    }


}
