@extends('layouts.app')

@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-6 text-slate-800 dark:text-slate-100">Résultats de la recherche</h1>
    <form method="GET" action="{{ route('tasks.search') }}" class="mb-6">
        <div class="relative max-w-lg mx-auto">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher une tâche..." class="w-full pl-10 pr-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
        </div>
    </form>
    @if($tasks->count())
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($tasks as $task)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700">
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $task->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $task->description }}</p>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Créée le {{ $task->created_at->format('d/m/Y') }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-gray-500 dark:text-gray-400 mt-8 text-center">Aucune tâche trouvée pour "{{ $query }}".</div>
    @endif
</div>
@endsection
