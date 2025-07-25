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

        $projets = Project::where('user_id', Auth::user()->id)
            ->select('id', 'name', 'slug', 'description', 'created_at')
            ->get();

        $sharedProjects = Auth::user()->sharedProjects()
            ->select('projects.id', 'projects.name', 'projects.slug', 'projects.description', 'projects.created_at')
            ->get();

        return view('dashboard', compact('projets', 'sharedProjects', 'results'));
    }


}
