<div id="task-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[96vh] overflow-visible">
            <!-- Header -->
            <div class="flex items-center justify-between p-10 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-5">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Détails de la tâche</h2>
                        <p class="text-base text-gray-400 dark:text-gray-400 mt-1">Modifiez et gérez votre tâche</p>
                    </div>
                </div>
                <button id="close-task-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex gap-12 p-10 bg-gray-50 dark:bg-gray-900/80">
                <!-- Main Content -->
                <div class="flex-1 pr-10 space-y-10">
                    <!-- Title -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-heading text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Titre</label>
                        </div>
                        <input type="text" id="task-title" class="w-full text-2xl font-bold bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 outline-none text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 rounded-2xl px-6 py-4 transition-all duration-200 shadow-sm" placeholder="Titre de la tâche">
                    </div>

                    <!-- Description -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-align-left text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Description</label>
                        </div>
                        <textarea id="task-description" rows="5" class="w-full px-6 py-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white resize-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-lg shadow-sm" placeholder="Décrivez votre tâche en détail..."></textarea>
                    </div>

                    <!-- Tags -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-tags text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Tags</label>
                        </div>
                        <div class="flex flex-wrap gap-3 mb-4 p-4 bg-gray-100 dark:bg-gray-700/50 rounded-2xl min-h-[48px]" id="tags-container">
                            <!-- Tags will be added here -->
                        </div>
                        <div class="flex gap-4">
                            <input type="text" id="new-tag" class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-base focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Ajouter un tag...">
                            <button id="add-tag" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-md text-base flex items-center gap-2">
                                <i class="fas fa-plus"></i>Ajouter
                            </button>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-comments text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Commentaires</label>
                        </div>
                        <div id="comments-container" class="space-y-4 mb-4 min-h-[60px] p-4 bg-gray-100 dark:bg-gray-700/50 rounded-2xl">
                            <!-- Commentaires -->
                        </div>
                        <div class="flex gap-4">
                            <textarea id="new-comment" rows="1" class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white resize-none text-base focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Ajouter un commentaire..."></textarea>
                            <button id="add-comment" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-medium shadow-md text-base flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i>Envoyer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-[370px] border-l border-gray-200 dark:border-gray-700 pl-10 bg-white dark:bg-gray-900/70 flex-shrink-0 rounded-2xl shadow-md space-y-10">
                    <!-- Status -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-columns text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Statut</label>
                        </div>
                        <select id="task-status" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-base">
                            @foreach($projet->listTasks ?? [] as $listTask)
                                <option value="{{ $listTask->id }}">{{ $listTask->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-flag text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Priorité</label>
                        </div>
                        <select id="task-priority" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-base">
                            <option value="">Aucune</option>
                            <option value="basse">Basse</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-folder text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Catégorie</label>
                        </div>
                        <select id="task-category" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-base">
                            <option value="">Aucune</option>
                            <option value="marketing">Marketing</option>
                            <option value="développement">Développement</option>
                            <option value="communication">Communication</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-calendar-alt text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Date limite</label>
                        </div>
                        <input type="date" id="task-due-date" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all duration-200 text-base">
                    </div>

                    <!-- Assignees -->
                    <div>
                        <div class="flex items-center space-x-4 mb-3">
                            <i class="fas fa-users text-blue-500 text-lg"></i>
                            <label class="text-lg font-semibold text-gray-700 dark:text-gray-300">Assignés</label>
                        </div>
                        <div id="assignees-container" class="space-y-3 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-2xl min-h-[48px]">
                            <!-- Current assignees will be shown here -->
                        </div>
                        <div class="flex gap-3">
                            <select id="assignee-select" class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-base focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                <option value="">Sélectionner un membre...</option>
                                @foreach($projet->members ?? [] as $membre)
                                    <option value="{{ $membre->id }}">{{ $membre->name }}</option>
                                @endforeach
                            </select>
                            <button id="add-assignee" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-medium shadow-md text-base flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button id="save-task" class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl text-lg flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>Sauvegarder
                        </button>
                        <button id="delete-task" class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-2xl hover:from-red-600 hover:to-pink-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl text-lg flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>Supprimer la tâche
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTaskId = null;
let currentTask = null;

function openTaskModal(taskId) {
    currentTaskId = taskId;
    
    // Fetch task data
    fetch(`/api/tasks/${taskId}`)
        .then(response => response.json())
        .then(data => {
            currentTask = data.task;
            populateModal(data.task);
            document.getElementById('task-modal').classList.remove('hidden');
        })
        .catch(error => {
            showNotification('Erreur', 'Impossible de charger les détails de la tâche', 'error');
        });
}

function populateModal(task) {
    // Basic fields
    document.getElementById('task-title').value = task.title || '';
    document.getElementById('task-description').value = task.description || '';
    document.getElementById('task-status').value = task.list_task_id || '';
    document.getElementById('task-priority').value = task.priorite || '';
    document.getElementById('task-category').value = task.categorie || '';
    document.getElementById('task-due-date').value = task.date_limite ? task.date_limite.split('T')[0] : '';
    
    // Tags
    populateTags(task.tags || []);
    
    // Comments
    populateComments(task.comments || []);
    
    // Assignees
    populateAssignees(task.assignes || []);
}

function populateTags(tags) {
    const container = document.getElementById('tags-container');
    container.innerHTML = '';
    
    tags.forEach(tag => {
        const tagElement = createTagElement(tag);
        container.appendChild(tagElement);
    });
}

function createTagElement(tag) {
    const div = document.createElement('div');
    div.className = 'inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm';
    div.innerHTML = `
        <span>${tag}</span>
        <button onclick="removeTag(this)" class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    return div;
}

function populateComments(comments) {
    const container = document.getElementById('comments-container');
    container.innerHTML = '';
    
    comments.forEach(comment => {
        const commentElement = createCommentElement(comment);
        container.appendChild(commentElement);
    });
}

function createCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'bg-gray-50 dark:bg-gray-700 rounded-lg p-3';
    div.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <img src="${comment.user.avatar || '/default-avatar.png'}" alt="${comment.user.name}" class="w-6 h-6 rounded-full">
            <span class="text-sm font-medium text-gray-900 dark:text-white">${comment.user.name}</span>
            <span class="text-xs text-gray-500">${new Date(comment.created_at).toLocaleDateString()}</span>
        </div>
        <p class="text-sm text-gray-700 dark:text-gray-300">${comment.content}</p>
    `;
    return div;
}

function populateAssignees(assignees) {
    const container = document.getElementById('assignees-container');
    container.innerHTML = '';
    
    assignees.forEach(assignee => {
        const assigneeElement = createAssigneeElement(assignee);
        container.appendChild(assigneeElement);
    });
}

function createAssigneeElement(assignee) {
    const div = document.createElement('div');
    div.className = 'flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg';
    div.innerHTML = `
        <div class="flex items-center gap-2">
            <img src="${assignee.avatar || '/default-avatar.png'}" alt="${assignee.name}" class="w-6 h-6 rounded-full">
            <span class="text-sm text-gray-900 dark:text-white">${assignee.name}</span>
        </div>
        <button onclick="removeAssignee(${assignee.id})" class="text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    return div;
}

// Event listeners
document.getElementById('close-task-modal').addEventListener('click', () => {
    document.getElementById('task-modal').classList.add('hidden');
});

document.getElementById('add-tag').addEventListener('click', () => {
    const input = document.getElementById('new-tag');
    const tag = input.value.trim();
    
    if (tag) {
        const container = document.getElementById('tags-container');
        const tagElement = createTagElement(tag);
        container.appendChild(tagElement);
        input.value = '';
    }
});

document.getElementById('add-comment').addEventListener('click', () => {
    const textarea = document.getElementById('new-comment');
    const content = textarea.value.trim();
    
    if (content) {
        addComment(content);
        textarea.value = '';
    }
});

document.getElementById('add-assignee').addEventListener('click', () => {
    const select = document.getElementById('assignee-select');
    const assigneeId = select.value;
    
    if (assigneeId) {
        addAssignee(assigneeId);
        select.value = '';
    }
});

document.getElementById('save-task').addEventListener('click', saveTask);
document.getElementById('delete-task').addEventListener('click', deleteCurrentTask);

// Helper functions
function removeTag(button) {
    button.parentElement.remove();
}

function removeAssignee(assigneeId) {
    if (confirm('Êtes-vous sûr de vouloir retirer cet assigné ?')) {
        fetch(`/api/tasks/${currentTaskId}/assignees/${assigneeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove from UI
                const assigneeElements = document.querySelectorAll(`[data-assignee-id="${assigneeId}"]`);
                assigneeElements.forEach(el => el.remove());
                showNotification('Succès', 'Assigné retiré avec succès', 'success');
            } else {
                showNotification('Erreur', data.message || 'Erreur lors du retrait', 'error');
            }
        });
    }
}

function addComment(content) {
    fetch(`/api/tasks/${currentTaskId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('comments-container');
            const commentElement = createCommentElement(data.comment);
            container.appendChild(commentElement);
            showNotification('Succès', 'Commentaire ajouté', 'success');
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de l\'ajout du commentaire', 'error');
        }
    });
}

function addAssignee(assigneeId) {
    fetch(`/api/tasks/${currentTaskId}/assignees`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_id: assigneeId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('assignees-container');
            const assigneeElement = createAssigneeElement(data.assignee);
            container.appendChild(assigneeElement);
            showNotification('Succès', 'Assigné ajouté avec succès', 'success');
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de l\'ajout', 'error');
        }
    });
}

function saveTask() {
    const formData = {
        title: document.getElementById('task-title').value,
        description: document.getElementById('task-description').value,
        status: document.getElementById('task-status').value,
        priority: document.getElementById('task-priority').value,
        category: document.getElementById('task-category').value,
        due_date: document.getElementById('task-due-date').value,
        tags: Array.from(document.getElementById('tags-container').children).map(tag => tag.querySelector('span').textContent)
    };

    fetch(`/api/tasks/${currentTaskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Succès', 'Tâche mise à jour avec succès', 'success');
            // Refresh the task list if needed
            if (typeof refreshTaskList === 'function') {
                refreshTaskList();
            }
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de la sauvegarde', 'error');
        }
    });
}

function deleteCurrentTask() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
        fetch(`/api/tasks/${currentTaskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('task-modal').classList.add('hidden');
                showNotification('Succès', 'Tâche supprimée avec succès', 'success');
                // Remove from UI
                const taskRow = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                if (taskRow) taskRow.remove();
            } else {
                showNotification('Erreur', data.message || 'Erreur lors de la suppression', 'error');
            }
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !document.getElementById('task-modal').classList.contains('hidden')) {
        document.getElementById('task-modal').classList.add('hidden');
    }
});
</script>
