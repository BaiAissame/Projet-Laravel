<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('dashboard', compact('projets'));
    }

    public function show(Project $projet)
    {
        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('projet.show', compact('projets'));
    }
}
