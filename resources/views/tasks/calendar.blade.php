@extends('layouts.app')

@section('content')
<!-- Sortir complètement du conteneur flex du layout -->
<div class="fixed inset-0 top-16 bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 transition-all duration-300 overflow-auto">
    <!-- Header avec glassmorphism -->
    <div class="sticky top-0 z-30 backdrop-blur-md bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/50 dark:border-slate-700/50 shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Titre -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                            {{ $projet->name }}
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400">Calendrier des tâches</p>
                    </div>
                </div>

                <!-- Boutons de navigation -->
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

                        <!-- ✅ Bouton Kanban -->
                <a href="{{ route('projet.show', ['projet' => $projet->slug]) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 shadow-sm">
                    <i class="fas fa-columns mr-2"></i>
                    Kanban
                </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <!-- Contrôles de navigation -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Navigation temporelle -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center bg-white/80 dark:bg-slate-800/80 rounded-xl p-1 shadow-lg backdrop-blur-sm">
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

                <!-- Sélecteur de vue -->
                <div class="flex items-center bg-white/80 dark:bg-slate-800/80 rounded-xl p-1 shadow-lg backdrop-blur-sm">
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

        <!-- Calendrier -->
        <div class="bg-white/80 dark:bg-slate-800/80 rounded-2xl shadow-xl backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
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

<!-- Modal moderne pour voir/éditer une tâche -->
<div id="taskModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="taskModalContent">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-slate-700 dark:to-slate-600">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100" id="taskModalTitle">
                    Détails de la tâche
                </h3>
                <button onclick="closeTaskModal()" class="p-2 hover:bg-white/50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                    <i class="fas fa-times text-slate-500 dark:text-slate-400"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 max-h-96 overflow-y-auto" id="taskModalBody">
            <!-- Contenu dynamique -->
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50 flex justify-end space-x-3">
            <button onclick="closeTaskModal()" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                Fermer
            </button>
            <button onclick="editTaskInModal()" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </button>
        </div>
    </div>
</div>

<script>
function showTaskDetails(taskId) {
    // Afficher les détails de la tâche dans le modal
    fetch(`/task/details/${taskId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('taskModalTitle').textContent = data.title;
            document.getElementById('taskModalBody').innerHTML = `
                <div class="space-y-6">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Description</h4>
                        <p class="text-slate-600 dark:text-slate-300">${data.description || 'Aucune description'}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                            <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Catégorie</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                ${data.category || 'Aucune'}
                            </span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                            <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Priorité</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${data.priority == 'élevée' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : data.priority == 'moyenne' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'}">
                                ${data.priority}
                            </span>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Assigné à</h4>
                        <div class="flex flex-wrap gap-2">
                            ${data.users ? data.users.map(user => `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">${user.name}</span>`).join('') : '<span class="text-slate-500 dark:text-slate-400">Non assigné</span>'}
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 dark:text-slate-100 mb-2">Date limite</h4>
                        <p class="text-slate-600 dark:text-slate-300">
                            ${data.due_date ? new Date(data.due_date).toLocaleDateString('fr-FR', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            }) : 'Non définie'}
                        </p>
                    </div>
                </div>
            `;
            showTaskModal();
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des détails:', error);
        });
}

function showTaskModal() {
    const modal = document.getElementById('taskModal');
    const content = document.getElementById('taskModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animation d'ouverture
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeTaskModal() {
    const modal = document.getElementById('taskModal');
    const content = document.getElementById('taskModalContent');
    
    // Animation de fermeture
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function editTaskInModal() {
    // Rediriger vers la page d'édition ou ouvrir un modal d'édition
    closeTaskModal();
}

// Fonction pour mettre à jour la date limite par glisser-déposer
function updateTaskDueDate(taskId, newDate) {
    fetch(`/task/update-due-date/${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            due_date: newDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showNotification('Erreur', data.message, 'error');
            location.reload();
        } else {
            showNotification('Succès', 'Date limite mise à jour', 'success');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur', 'Une erreur est survenue', 'error');
    });
}

// Fermer le modal en cliquant en dehors
document.getElementById('taskModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTaskModal();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTaskModal();
    }
});
</script>

<style>
/* Styles personnalisés pour le calendrier */
.calendar-day {
    @apply min-h-32 border border-slate-200 dark:border-slate-700 p-2 bg-white dark:bg-slate-800 transition-all duration-200 hover:bg-slate-50 dark:hover:bg-slate-700;
}

.calendar-day.today {
    @apply bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-blue-300 dark:border-blue-600;
}

.calendar-day.other-month {
    @apply bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-600;
}

.task-item {
    @apply bg-gradient-to-r from-blue-500 to-purple-600 text-white px-2 py-1 my-1 rounded-md text-xs cursor-pointer transition-all duration-200 hover:from-blue-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105;
    word-wrap: break-word;
}

.task-item.priority-elevee {
    @apply from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700;
}

.task-item.priority-moyenne {
    @apply from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700;
}

.task-item.priority-basse {
    @apply from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700;
}

.calendar-header {
    @apply bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 font-bold text-center py-3 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100;
}

.time-slot {
    @apply h-16 border-b border-slate-200 dark:border-slate-700 relative bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200;
}

.task-in-slot {
    @apply absolute left-1 right-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-2 py-1 rounded text-xs cursor-pointer z-10 transition-all duration-200 hover:from-blue-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    @apply bg-slate-100 dark:bg-slate-800;
}

::-webkit-scrollbar-thumb {
    @apply bg-slate-300 dark:bg-slate-600 rounded-full;
}

::-webkit-scrollbar-thumb:hover {
    @apply bg-slate-400 dark:bg-slate-500;
}
</style>
@endsection