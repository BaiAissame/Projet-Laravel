@extends('layouts.app')

@section('content')
    <div
        class="fixed inset-0 top-16 bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 transition-all duration-300 overflow-auto">
        <div
            class="sticky top-0 z-30 backdrop-blur-md bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/50 dark:border-slate-700/50 shadow-lg">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                                {{ $projet->name }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400">Liste des tâches</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('tasks.list', $projet) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 shadow-sm">
                            <i class="fas fa-list mr-2"></i>
                            Liste
                        </a>
                        <a href="{{ route('tasks.calendar', $projet) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-calendar mr-2"></i>
                            Calendrier
                        </a>

                        <a href="{{ route('projet.show', ['projet' => $projet->slug]) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 shadow-sm">
                            <i class="fas fa-columns mr-2"></i>
                            Kanban
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <div
                class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-200/50 dark:border-slate-700/50 flex-shrink-0">
                    <form method="GET" action="{{ route('tasks.list', $projet) }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-slate-400 dark:text-slate-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" name="search" placeholder="Rechercher une tâche..."
                                        value="{{ request('search') }}"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <select name="category"
                                    class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Toutes catégories</option>
                                    <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="développement" {{ request('category') == 'développement' ? 'selected' : '' }}>Développement</option>
                                    <option value="communication" {{ request('category') == 'communication' ? 'selected' : '' }}>Communication</option>
                                </select>
                            </div>

                            <div>
                                <select name="priority"
                                    class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Toutes priorités</option>
                                    <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>Basse
                                    </option>
                                    <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne
                                    </option>
                                    <option value="élevée" {{ request('priority') == 'élevée' ? 'selected' : '' }}>Élevée
                                    </option>
                                </select>
                            </div>

                            <div>
                                <select name="assigned_user"
                                    class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Tous les utilisateurs</option>
                                    @foreach($projectUsers as $user)
                                        <option value="{{ $user->id }}" {{ request('assigned_user') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select name="sort_by"
                                    class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date
                                        création</option>
                                    <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                                    <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priorité
                                    </option>
                                    <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Date
                                        limite</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 px-6 py-3 rounded-xl text-white font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span>Rechercher</span>
                            </button>
                            <a href="{{ route('tasks.list', $projet) }}"
                                class="bg-slate-200 dark:bg-slate-700/50 hover:bg-slate-300 dark:hover:bg-slate-600/50 px-6 py-3 rounded-xl text-slate-700 dark:text-white font-medium transition-all duration-200 flex items-center space-x-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Réinitialiser</span>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="flex-1 overflow-auto">
                    <table class="w-full">
                        <thead class="sticky top-0 z-10">
                            <tr
                                class="bg-slate-100 dark:bg-slate-700/50 backdrop-blur-sm border-b border-slate-200/50 dark:border-slate-700/50">
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Titre</th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Description
                                </th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Catégorie
                                </th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Priorité
                                </th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Liste</th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Assigné à
                                </th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Date limite
                                </th>
                                <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr
                                    class="border-b border-slate-200/30 dark:border-slate-700/30 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-all duration-200">
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $task->title }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-slate-600 dark:text-slate-300 max-w-xs">
                                            {{ Str::limit($task->description, 50) }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($task->category)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/30">
                                                {{ $task->category }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $priorityStyles = [
                                                'basse' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800/30',
                                                'moyenne' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800/30',
                                                'élevée' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800/30'
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $priorityStyles[$task->priority] ?? 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-800/30' }} border">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800/30">
                                            {{ $task->listTask->title }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($task->users->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($task->users as $user)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/30">
                                                        {{ $user->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 text-sm">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($task->due_date)
                                            <span
                                                class="text-sm {{ Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 text-sm">Non définie</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex space-x-2">
                                            <button onclick="openTaskModal('{{ $task->id }}')"
                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 border border-blue-200 dark:border-blue-800/30"
                                                title="Modifier">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteTask('{{ $task->id }}')"
                                                class="p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 border border-red-200 dark:border-red-800/30"
                                                title="Supprimer">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center">
                                        <div class="flex flex-col items-center space-y-4">
                                            <svg class="h-12 w-12 text-slate-400 dark:text-slate-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                </path>
                                            </svg>
                                            <div class="text-slate-600 dark:text-slate-400 text-lg">Aucune tâche trouvée</div>
                                            <div class="text-slate-500 dark:text-slate-600 text-sm">Essayez de modifier vos
                                                filtres de recherche</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200/50 dark:border-slate-700/50 flex-shrink-0">
                        <div class="flex justify-center">
                            {{ $tasks->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@include('components.task-modal')
@endsection