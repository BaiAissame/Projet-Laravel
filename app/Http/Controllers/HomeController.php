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

        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('dashboard', compact('projets', 'results'));
    }

    public function show(Project $projet)
    {
        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('projet.show', compact('projets'));
    }
}
