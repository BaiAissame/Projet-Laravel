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
                ->get();
        }

        // Projets créés par l'utilisateur
        $projets = Project::where('user_id', Auth::user()->id)->get();
        
        // Projets partagés avec l'utilisateur (où il est membre)
        $sharedProjects = Auth::user()->sharedProjects;
        
        return view('dashboard', compact('projets', 'sharedProjects', 'results'));
    }

    public function show(Project $projet)
    {
         if ($projet->user_id != Auth::id() && !$projet->members->contains(Auth::id())) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à ce projet.');
        }
        
        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('projet.show', compact('projets', 'projet'));
    }
}
