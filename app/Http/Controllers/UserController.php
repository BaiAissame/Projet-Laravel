<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
  public function index()
  {
    $users = User::all();
    return view('users.index', compact('users'));
  }

  public function editRole($id)
  {
    $user = User::findOrFail($id);
    $roles = config('laratrust.roles', ['admin', 'user']); // ['admin', 'user', ...]
    return view('users.edit_role', compact('user', 'roles'));
  }

  public function updateRole(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $role = $request->input('role');
    $roles = config('laratrust.roles', ['admin', 'user']);
    foreach ($roles as $r) {
      $user->removeRole($r);
    }
    $user->addRole($role);
    return Redirect::route('users.index')->with('success', 'Rôle mis à jour !');
  }
}
