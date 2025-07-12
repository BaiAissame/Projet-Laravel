<!-- Modal d'ajout de membre -->
<div id="addMemberModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="add-member-content">
        <form id="addMemberForm" method="POST" class="p-8">
            @csrf
            
            <!-- En-tête de la modal -->
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTitle">Ajouter un membre</h3>
                    <p class="text-gray-600 dark:text-gray-400">Invitez quelqu'un à rejoindre le projet</p>
                </div>
                <button type="button" onclick="closeAddMemberModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Champs du formulaire -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email du membre</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               required 
                               class="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                               placeholder="membre@example.com"
                               style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Le membre doit être inscrit sur la plateforme
                    </p>
                    @error('email')
                        <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex space-x-4 mt-8">
                <button type="button" 
                        onclick="closeAddMemberModal()" 
                        class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddMemberModal(projectSlug, projectName) {
        document.getElementById('modalTitle').textContent = `Ajouter un membre au projet "${projectName}"`;
        document.getElementById('addMemberForm').action = `/projet/${projectSlug}/members`;
        
        const modal = document.getElementById('addMemberModal');
        const content = document.getElementById('add-member-content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAddMemberModal() {
        const modal = document.getElementById('addMemberModal');
        const content = document.getElementById('add-member-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);

        document.getElementById('addMemberForm').reset();
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('addMemberModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddMemberModal();
        }
    });

    // Fermer la modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddMemberModal();
        }
    });
</script>