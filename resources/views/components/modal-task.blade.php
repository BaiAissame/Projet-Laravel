<div id="task-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Détails de la tâche</h2>
                </div>
                <button id="close-task-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex h-[calc(90vh-120px)]">
                <!-- Main Content -->
                <div class="flex-1 p-6 overflow-y-auto">
                    <!-- Title -->
                    <div class="mb-6">
                        <input type="text" id="task-title" class="w-full text-2xl font-bold bg-transparent border-none outline-none text-gray-900 dark:text-white placeholder-gray-400" placeholder="Titre de la tâche">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="task-description" rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ajouter une description..."></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                        <div class="flex flex-wrap gap-2 mb-2" id="tags-container">
                            <!-- Tags will be added here -->
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="new-tag" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" placeholder="Ajouter un tag...">
                            <button id="add-tag" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">Ajouter</button>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Commentaires</label>
                        <div id="comments-container" class="space-y-3 mb-3 max-h-48 overflow-y-auto">
                            <!-- Comments will be added here -->
                        </div>
                        <div class="flex gap-2">
                            <textarea id="new-comment" rows="2" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none text-sm" placeholder="Ajouter un commentaire..."></textarea>
                            <button id="add-comment" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm self-end">Envoyer</button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-80 border-l border-gray-200 dark:border-gray-700 p-6 overflow-y-auto">
                    <!-- Status -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut</label>
                        <select id="task-status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @foreach($projet->listTasks ?? [] as $listTask)
                                <option value="{{ $listTask->id }}">{{ $listTask->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priorité</label>
                        <select id="task-priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Aucune</option>
                            <option value="basse">Basse</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie</label>
                        <select id="task-category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Aucune</option>
                            <option value="marketing">Marketing</option>
                            <option value="développement">Développement</option>
                            <option value="communication">Communication</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date limite</label>
                        <input type="date" id="task-due-date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Assignees -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assignés</label>
                        <div id="assignees-container" class="space-y-2 mb-2">
                            <!-- Current assignees will be shown here -->
                        </div>
                        <div class="flex gap-2">
                            <select id="assignee-select" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="">Sélectionner un membre...</option>
                                @foreach($projet->members ?? [] as $membre)
                                    <option value="{{ $membre->id }}">{{ $membre->name }}</option>
                                @endforeach
                            </select>
                            <button id="add-assignee" class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">+</button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <button id="save-task" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Sauvegarder
                        </button>
                        <button id="delete-task" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Supprimer la tâche
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
            console.error('Error fetching task:', error);
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
