<div id="addMemberModal" class="fixed inset-0 z-50 overflow-auto bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Ajouter un membre au projet</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeAddMemberModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="addMemberForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email du membre</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="text-xs text-gray-500 mt-1">Le membre doit être inscrit sur la plateforme</p>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400" onclick="closeAddMemberModal()">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddMemberModal(projectSlug, projectName) {
        document.getElementById('modalTitle').textContent = `Ajouter un membre au projet "${projectName}"`;
    
        document.getElementById('addMemberForm').action = `/projet/${projectSlug}/members`;
        document.getElementById('addMemberModal').classList.remove('hidden');
    }

    function closeAddMemberModal() {
        document.getElementById('addMemberModal').classList.add('hidden');

        document.getElementById('addMemberForm').reset();
    }
</script>