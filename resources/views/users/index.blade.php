@extends('layouts.app')
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-gradient dark:text-gray-200">Gestion des utilisateurs</h1>
    <span class="badge bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-2 shadow">{{ $users->count() }}
      utilisateurs</span>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="card shadow-lg rounded-3xl bg-white">
    <div class="card-body p-0  rounded-3xl overflow-hidden">
      <table class="table table-hover align-middle mb-0 rounded-3xl overflow-hidden">
      <thead class="bg-gradient-to-r from-blue-100 to-purple-100 dark:bg-gray-800 dark:text-gray-200">
        <tr class="bg-white dark:bg-gray-800">
        <th class="text-center dark:bg-gray-800 dark:text-gray-200">Avatar</th>
        <th class="dark:bg-gray-800 dark:text-gray-200">Nom</th>
        <th class="dark:bg-gray-800 dark:text-gray-200">Email</th>
        <th class="dark:bg-gray-800 text-center dark:text-gray-200">Rôle</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
      <tr class="bg-white dark:bg-gray-900 dark:text-gray-200">
      <td class="text-center bg-white dark:bg-gray-900 dark:text-gray-200">
        <div
        class="rounded-circle bg-gradient-to-r from-blue-500 to-purple-600 text-white fw-bold d-inline-flex align-items-center justify-content-center"
        style="width:40px;height:40px;font-size:1.2rem;">
        {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
      </td>
      <td class="fw-semibold bg-white dark:bg-gray-900 dark:text-white">{{ $user->name }}</td>
      <td class="text-muted bg-white dark:bg-gray-900 dark:text-gray-400">{{ $user->email }}</td>
      <td class="text-center bg-white dark:bg-gray-900 dark:text-gray-200">
        <form method="POST" action="{{ route('users.updateRole', $user->id) }}" class="d-inline">
        @csrf
        @method('PUT')
        <select name="role" onchange="this.form.submit()"
        class="form-select form-select-sm border-0 bg-gradient-to-r from-blue-50 to-purple-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 shadow-sm fw-bold">
        @foreach(config('laratrust.roles', ['admin', 'user']) as $role)
      <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>{{ ucfirst($role) }}
      </option>
      @endforeach
        </select>
        </form>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
    </div>
    </div>
  </div>
@endsection