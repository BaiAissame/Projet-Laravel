<!-- Modal de création de projet -->
<div id="create-project-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="create-project-content">
        <form action="{{ route('tableau.create') }}" method="POST" class="p-8">
            @csrf
            
            <!-- En-tête de la modal -->
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-plus text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Créer un nouveau projet</h3>
                    <p class="text-gray-600 dark:text-gray-400">Organisez vos tâches avec un tableau Kanban</p>
                </div>
                <button type="button" onclick="closeCreateProjectModal()" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Champs du formulaire -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="table-name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Nom du projet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="table-name" 
                               name="name" 
                               required 
                               class="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               placeholder="Mon nouveau projet"
                               style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
                    </div>
                    @error('name')
                        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="table-description" class="text-sm font-medium text-gray-700 dark:text-gray-300">Description (optionnel)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-align-left text-gray-400"></i>
                        </div>
                        <textarea id="table-description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none" 
                                  placeholder="Décrivez votre projet..."
                                  style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);"></textarea>
                    </div>
                    @error('description')
                        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex space-x-4 mt-8">
                <button type="button" 
                        onclick="closeCreateProjectModal()" 
                        class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Créer le projet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateProjectModal() {
        const modal = document.getElementById('create-project-modal');
        const content = document.getElementById('create-project-content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCreateProjectModal() {
        const modal = document.getElementById('create-project-modal');
        const content = document.getElementById('create-project-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('create-project-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateProjectModal();
        }
    });

    // Fermer la modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateProjectModal();
        }
    });
</script>
