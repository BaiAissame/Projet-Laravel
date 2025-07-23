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
                        <p class="text-slate-600 dark:text-slate-400">Calendrier des tâches</p>
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
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="flex items-center bg-white/80 dark:bg-slate-800/80 rounded-xl p-1 shadow-lg backdrop-blur-sm">
                        <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => $currentDate->copy()->subDay()->format('Y-m-d')])) }}"
                            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                            <i class="fas fa-chevron-left text-slate-600 dark:text-slate-400"></i>
                        </a>
                        <div class="px-6 py-2 text-slate-800 dark:text-slate-200 font-semibold min-w-0">
                            @if($view == 'day')
                                {{ $currentDate->format('d/m/Y') }}
                            @elseif($view == 'threeDays')
                                {{ $currentDate->format('d/m') }} - {{ $currentDate->copy()->addDays(2)->format('d/m/Y') }}
                            @elseif($view == 'week')
                                {{ $startDate->format('d/m') }} - {{ $endDate->format('d/m/Y') }}
                            @else
                                {{ $currentDate->format('F Y') }}
                            @endif
                        </div>
                        <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => $currentDate->copy()->addDay()->format('Y-m-d')])) }}"
                            class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                            <i class="fas fa-chevron-right text-slate-600 dark:text-slate-400"></i>
                        </a>
                    </div>
                    <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => now()->format('Y-m-d')])) }}"
                        class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 shadow-lg">
                        Aujourd'hui
                    </a>
                </div>

                <div
                    class="flex items-center bg-white/80 dark:bg-slate-800/80 rounded-xl p-1 shadow-lg backdrop-blur-sm">
                    <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'day'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $view == 'day' ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        Jour
                    </a>
                    <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'threeDays'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $view == 'threeDays' ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        3 Jours
                    </a>
                    <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'week'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $view == 'week' ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        Semaine
                    </a>
                    <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'month'])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $view == 'month' ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        Mois
                    </a>
                </div>
            </div>
        </div>

        <div
            class="bg-white/80 dark:bg-slate-800/80 rounded-2xl shadow-xl backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
            @if($view == 'day')
                @include('tasks.calendar.day')
            @elseif($view == 'threeDays')
                @include('tasks.calendar.three-days')
            @elseif($view == 'week')
                @include('tasks.calendar.week')
            @else
                @include('tasks.calendar.month')
            @endif
        </div>
    </div>
</div>

@include('components.task-modal')