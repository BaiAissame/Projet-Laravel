@once
  @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let currentTaskId = null;
    let currentTask = null;
    let currentColumnId = null;
    let selectedColor = null;
    function openTaskModal(taskId) {
    window.openTaskModal = openTaskModal;
    currentTaskId = taskId;

    fetch(`/api/tasks/${taskId}`)
      .then(response => response.json())
      .then(data => {
      currentTask = data.task;
      populateModal(data.task);

      const modal = document.getElementById('task-modal');
      const modalContent = document.getElementById('task-modal-content');

      modal.classList.remove('hidden');
      setTimeout(() => {
      modalContent.classList.remove('scale-95', 'opacity-0');
      modalContent.classList.add('scale-100', 'opacity-100');
      }, 10);
      })
      .catch(error => {
      showNotification('Erreur', 'Impossible de charger les détails de la tâche', 'error');
      });
    }

    function populateModal(task) {
    document.getElementById('task-title').value = task.title || '';
    document.getElementById('task-description').value = task.description || '';
    document.getElementById('task-status').value = task.list_task_id || '';
    document.getElementById('task-priority').value = task.priority || '';
    document.getElementById('task-category').value = task.category || '';

    let dateValue = '';
    if (task.due_date) {
      try {
      if (task.due_date.includes('/')) {
      const parts = task.due_date.split('/');
      if (parts.length === 3) {
      dateValue = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
      }
      } else if (task.due_date.includes('-')) {
      dateValue = task.due_date.split('T')[0];
      } else {
      const date = new Date(task.due_date);
      if (!isNaN(date.getTime())) {
      dateValue = date.toISOString().split('T')[0];
      }
      }
      } catch (error) {
      dateValue = '';
      }
    }
    document.getElementById('task-due-date').value = dateValue;
    const tagNames = Array.isArray(task.tags) ? task.tags.map(tag => typeof tag === 'string' ? tag : (tag.name || tag)) : [];
    populateTags(tagNames);
    populateComments(task.comments || []);
    populateAssignees(task.assignes || []);
    }

    function populateTags(tags) {
    const container = document.getElementById('tags-container'); if (!container) {
      return;
    }

    container.innerHTML = '';

    if (!tags || tags.length === 0) {
      container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm empty-tag">Aucun tag ajouté</div>';
    } else {
      tags.forEach(tag => {
      const tagElement = createTagElement(tag);
      if (tagElement) {
      container.appendChild(tagElement);
      }
      });
    }
    }

    function createTagElement(tag) {
    if (!tag || typeof tag !== 'string') {
      return null;
    }

    const div = document.createElement('div');
    div.className = 'inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200 transform hover:scale-105';
    div.innerHTML = `
      <span>${tag}</span>
      <button onclick="removeTag(this)" class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100 hover:bg-blue-200 dark:hover:bg-blue-800/50 rounded-full p-1 transition-all duration-200">
      <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
      </button>
      `;
    return div;
    }

    function populateComments(comments) {
    const container = document.getElementById('comments-container');
    if (!container) {
      return;
    }

    container.innerHTML = '';

    if (!comments || comments.length === 0) {
      container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm text-center py-6 empty-comment">Aucun commentaire</div>';
      return;
    }

    comments.forEach(comment => {
      const commentElement = createCommentElement(comment);
      if (commentElement) {
      container.appendChild(commentElement);
      }
    });
    }

    function createCommentElement(comment) {
    if (!comment) {
      return null;
    }

    const div = document.createElement('div');
    div.className = 'bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-all duration-200';

    const userName = comment.user?.name || 'Utilisateur';
    const userInitial = userName.charAt(0).toUpperCase();
    const commentDate = comment.created_at ? new Date(comment.created_at).toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }) : 'Date inconnue';
    const commentContent = comment.content || 'Contenu vide';

    div.innerHTML = `
      <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
      ${userInitial}
      </div>
      <div class="flex-1">
      <span class="text-sm font-medium text-gray-900 dark:text-white">${userName}</span>
      <div class="text-xs text-gray-500 dark:text-gray-400">${commentDate}</div>
      </div>
      </div>
      <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">${commentContent}</p>
      `;
    return div;
    }

    function populateAssignees(assignees) {
    const container = document.getElementById('assignees-container');
    if (!container) {
      return;
    }

    container.innerHTML = '';

    if (!assignees || assignees.length === 0) {
      container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm">Aucun assigné</div>';
      return;
    }

    assignees.forEach(assignee => {
      const assigneeElement = createAssigneeElement(assignee);
      if (assigneeElement) {
      container.appendChild(assigneeElement);
      }
    });
    }

    function createAssigneeElement(assignee) {
    if (!assignee || !assignee.id) {
      return null;
    }

    const div = document.createElement('div');
    div.className = 'flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-all duration-200';
    div.setAttribute('data-assignee-id', assignee.id);
    div.innerHTML = `
      <div class="flex items-center gap-3">
      <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
      ${assignee.name ? assignee.name.charAt(0).toUpperCase() : 'U'}
      </div>
      <span class="text-sm font-medium text-gray-900 dark:text-white">${assignee.name || 'Utilisateur'}</span>
      </div>
      <button onclick="removeAssignee(${assignee.id})" class="w-6 h-6 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full flex items-center justify-center transition-all duration-200">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
      </svg>
      </button>
      `;
    return div;
    }

    document.getElementById('close-task-modal').addEventListener('click', () => {
    closeTaskModal();
    });

    function closeTaskModal() {
    const modal = document.getElementById('task-modal');
    const modalContent = document.getElementById('task-modal-content');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
    }

    document.getElementById('add-tag').addEventListener('click', () => {
    const input = document.getElementById('new-tag');
    const tag = input.value.trim();

    if (tag) {
      const container = document.getElementById('tags-container');
      const placeholder = container.querySelector('.empty-tag');
      if (placeholder) {
      placeholder.remove();
      }
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

    function removeTag(button) {
    button.parentElement.remove();
    }

    function removeAssignee(assigneeId) {
    if (confirm('Êtes-vous sûr de vouloir retirer cet assigné ?')) {
      fetch(`/api/tasks/${currentTaskId}/assignees/${assigneeId}`, {
      method: 'DELETE'
      })
      .then(response => response.json())
      .then(data => {
      if (data.success) {
      const assigneeElements = document.querySelectorAll(`[data-assignee-id="${assigneeId}"]`);
      assigneeElements.forEach(el => el.remove());
      showNotification('Succès', 'Assigné retiré avec succès', 'success');
      updateTaskCard();
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
      'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ content })
    })
      .then(response => response.json())
      .then(data => {
      if (data.success) {
      const container = document.getElementById('comments-container');
      const placeholder = container.querySelector('.empty-comment');
      if (placeholder) {
      placeholder.remove();
      }
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
      'Content-Type': 'application/json'
      },
      body: JSON.stringify({ user_id: assigneeId })
    })
      .then(response => response.json())
      .then(data => {
      if (data.success) {
      const container = document.getElementById('assignees-container');
      const assigneeElement = createAssigneeElement(data.assignee);
      if (assigneeElement && container) {
      container.appendChild(assigneeElement);
      showNotification('Succès', 'Assigné ajouté avec succès', 'success');

      updateTaskCard();

      } else {
      showNotification('Erreur', 'Erreur lors de la création de l\'élément assigné', 'error');
      }
      } else {
      showNotification('Erreur', data.message || 'Erreur lors de l\'ajout', 'error');
      }
      });
    }

    function saveTask() {
    const tagsContainer = document.getElementById('tags-container');
    const tags = tagsContainer ? Array.from(tagsContainer.children)
      .filter(tag => tag.querySelector && tag.querySelector('span'))
      .map(tag => tag.querySelector('span').textContent)
      .filter(text => text && text.trim() !== '') : [];

    const formData = {
      title: document.getElementById('task-title')?.value || '',
      description: document.getElementById('task-description')?.value || '',
      status: document.getElementById('task-status')?.value || '',
      priority: document.getElementById('task-priority')?.value || '',
      category: document.getElementById('task-category')?.value || '',
      due_date: document.getElementById('task-due-date')?.value || '',
      tags: tags
    };

    fetch(`/api/tasks/${currentTaskId}`, {
      method: 'PUT',
      headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(formData)
    })
      .then(response => response.json())
      .then(data => {
      if (data.success) {
      showNotification('Succès', 'Tâche mise à jour avec succès', 'success');

      closeTaskModal();

      setTimeout(() => {
      updateTaskCard();
      }, 200);
      } else {
      showNotification('Erreur', data.message || 'Erreur lors de la sauvegarde', 'error');
      }
      });
    }

    function refreshTaskModal() {
    if (currentTaskId) {
      fetch(`/api/tasks/${currentTaskId}`)
      .then(response => response.json())
      .then(data => {
      currentTask = data.task;
      populateModal(data.task);
      })
      .catch(error => {
      });
    }
    }

    function updateTaskCard() {
    if (currentTaskId) {
      fetch(`/api/tasks/${currentTaskId}`)
      .then(response => response.json())
      .then(data => {
      const task = data.task;
      const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);

      if (taskCard && task) {
      const titleElement = taskCard.querySelector('h4');
      if (titleElement) {
        titleElement.textContent = task.title;
      }

      const descriptionElement = taskCard.querySelector('p.line-clamp-2');
      if (descriptionElement) {
        if (task.description) {
        descriptionElement.textContent = task.description.substring(0, 80) + (task.description.length > 80 ? '...' : '');
        descriptionElement.style.display = 'block';
        } else {
        descriptionElement.style.display = 'none';
        }
      }

      const assigneesContainer = taskCard.querySelector('.flex.-space-x-1');
      if (assigneesContainer) {
        updateTaskCardAssignees(assigneesContainer, task.assignes || []);
      }

      const dateElement = taskCard.querySelector('.fa-calendar-alt')?.parentElement;
      if (dateElement) {
        const spanElement = dateElement.querySelector('span');
        if (task.date_limite && spanElement) {
        const date = new Date(task.date_limite);
        spanElement.textContent = date.toLocaleDateString('fr-FR');
        dateElement.style.display = 'flex';
        } else if (spanElement) {
        dateElement.style.display = 'none';
        }
      }
      }
      })
      .catch(error => {
      });
    }
    }

    function updateTaskCardAssignees(container, assignees) {
    container.innerHTML = '';

    if (assignees && assignees.length > 0) {
      assignees.slice(0, 3).forEach(assignee => {
      const assigneeDiv = document.createElement('div');
      assigneeDiv.className = 'w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800';
      assigneeDiv.title = assignee.name;
      assigneeDiv.textContent = assignee.name.charAt(0).toUpperCase();
      container.appendChild(assigneeDiv);
      });

      if (assignees.length > 3) {
      const moreDiv = document.createElement('div');
      moreDiv.className = 'w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold border-2 border-white dark:border-gray-800';
      moreDiv.textContent = `+${assignees.length - 3}`;
      container.appendChild(moreDiv);
      }
    }
    }


    function deleteCurrentTask() {
    if (confirm('Supprimer cette tâche ?')) {
      const taskElement = document.querySelector(`[data-task-id="${currentTaskId}"]`);
      const columnElement = taskElement ? taskElement.closest('.droppable-zone') : null;
      const columnId = columnElement ? columnElement.dataset.colonne : null;

      fetch(`/task/delete/${currentTaskId}`, {
      method: 'DELETE',
      headers: {
      'X-CSRF-TOKEN': csrfToken
      }
      })
      .then(response => response.json())
      .then(data => {
      if (data.error) {
      showNotification('Erreur', data.message, 'error');
      } else {
      showNotification('Succès', 'Tâche supprimée', 'success');

      closeTaskModal();

      if (taskElement) {
        taskElement.remove();
        if (columnId) {
        updateEmptyColumnDisplay(columnId);
        }
      } else {
        location.reload();
      }
      }
      })
      .catch(error => {
      showNotification('Erreur', 'Erreur lors de la suppression', 'error');
      closeTaskModal();
      });
    }
    }

    function showNotification(title, message, type = 'info') {
    try {

      if (!title || !message) {
      return;
      }

      let notificationContainer = document.getElementById('notification-container');
      if (!notificationContainer) {
      notificationContainer = document.createElement('div');
      notificationContainer.id = 'notification-container';
      notificationContainer.style.cssText = `
      position: fixed !important;
      bottom: 25px !important;
      right: 200px !important;
      z-index: 99999 !important;
      display: flex !important;
      flex-direction: column !important;
      gap: 12px !important;
      pointer-events: none !important;
      `;
      document.body.appendChild(notificationContainer);
      }

      const existingNotifications = notificationContainer.querySelectorAll('.toast-notification');
      if (existingNotifications.length > 3) {
      existingNotifications[0].remove();
      }

      const notification = document.createElement('div');
      notification.className = 'toast-notification';

      let backgroundColor, iconClass;
      switch (type) {
      case 'success':
      backgroundColor = '#10b981';
      iconClass = 'fa-check-circle';
      break;
      case 'error':
      backgroundColor = '#ef4444';
      iconClass = 'fa-exclamation-circle';
      break;
      case 'warning':
      backgroundColor = '#f59e0b';
      iconClass = 'fa-exclamation-triangle';
      break;
      default:
      backgroundColor = '#3b82f6';
      iconClass = 'fa-info-circle';
      }

      notification.style.cssText = `
      background: ${backgroundColor} !important;
      color: white !important;
      padding: 16px !important;
      border-radius: 12px !important;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
      transform: translateX(100%) !important;
      transition: all 0.3s ease !important;
      min-width: 320px !important;
      max-width: 400px !important;
      font-size: 14px !important;
      font-weight: 500 !important;
      margin: 0 !important;
      position: relative !important;
      pointer-events: auto !important;
      border: none !important;
      outline: none !important;
      `;


      const safeTitle = title.replace(/[<>&"']/g, function (m) {
      return { '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&#39;' }[m];
      });
      const safeMessage = message.replace(/[<>&"']/g, function (m) {
      return { '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&#39;' }[m];
      });

      notification.innerHTML = `
      <div style="display: flex !important; align-items: center !important; gap: 12px !important;">
      <div style="flex-shrink: 0 !important;">
      <i class="fas ${iconClass}" style="font-size: 20px !important; color: white !important;"></i>
      </div>
      <div style="flex: 1 !important;">
      <h4 style="font-weight: 600 !important; margin: 0 0 4px 0 !important; color: white !important;">${safeTitle}</h4>
      <p style="margin: 0 !important; opacity: 0.9 !important; font-size: 13px !important; color: white !important;">${safeMessage}</p>
      </div>
      <button onclick="this.parentElement.parentElement.remove()" style="color: white !important; opacity: 0.8 !important; background: none !important; border: none !important; cursor: pointer !important; padding: 4px !important; font-size: 16px !important;">
      <i class="fas fa-times" style="color: white !important;"></i>
      </button>
      </div>
      `;

      notificationContainer.appendChild(notification);

      setTimeout(() => {
      notification.style.transform = 'translateX(0)';
      }, 100);

      setTimeout(() => {
      if (notification.parentNode) {
      notification.style.transform = 'translateX(100%)';
      setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
      }, 300);
      }
      }, 5000);

    } catch (error) {
    }
    }
    document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !document.getElementById('task-modal').classList.contains('hidden')) {
      closeTaskModal();
    }
    });

    document.getElementById('task-modal').addEventListener('click', (e) => {
    if (e.target.id === 'task-modal') {
      closeTaskModal();
    }
    });
    document.getElementById('close-task-modal').addEventListener('click', () => {
    closeTaskModal();
    });


    function openColorModal(columnId) {
    const modal = document.getElementById('color-modal');
    if (modal) {
      modal.classList.remove('hidden');
      currentColumnId = columnId;
      selectedColor = null;
      document.querySelectorAll('.color-option').forEach(opt => {
      opt.classList.remove('ring-4', 'ring-blue-300');
      });
    } else {
    }
    }

    function closeColorModal() {
    const modal = document.getElementById('color-modal');
    if (modal) {
      modal.classList.add('hidden');
    } else {
    }
    currentColumnId = null;
    selectedColor = null;
    document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
    });
    }

    function showColorPreview(color, colorName) {
    const preview = document.getElementById('color-preview');
    const previewBox = document.getElementById('preview-color-box');
    const previewName = document.getElementById('preview-color-name');

    if (preview && previewBox && previewName) {
      preview.classList.remove('hidden');

      previewBox.style.backgroundColor = color;
      previewBox.className = 'w-8 h-8 rounded-lg';
      previewName.textContent = colorName;
    }
    }

    window.closeColorModal = closeColorModal;
    window.openColorModal = openColorModal;

    function applyColumnColor(columnId, color) {
    const columnElement = document.querySelector(`[data-list-task-id="${columnId}"]`);
    if (columnElement) {

      columnElement.className = columnElement.className.replace(/border-\w+-\d+/g, '');
      columnElement.className = columnElement.className.replace(/bg-\w+-\d+/g, '');
      columnElement.className = columnElement.className.replace(/dark:border-\w+-\d+/g, '');
      columnElement.className = columnElement.className.replace(/dark:bg-\w+-\d+/g, '');

      columnElement.style.borderColor = color;
      columnElement.style.backgroundColor = color + '20';
      columnElement.setAttribute('data-color', color);

      fetch(`/listTask/update-color/${columnId}`, {
      method: 'POST',
      headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ color: color })
      })
      .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
      })
      .then(data => {
      if (data.success) {
      showNotification('Succès', 'Couleur de la colonne mise à jour', 'success');
      } else {
      showNotification('Erreur', data.message || 'Erreur lors de la mise à jour', 'error');
      }
      })
      .catch(error => {
      showNotification('Erreur', 'Erreur lors de la mise à jour de la couleur', 'error');
      });
    } else {
    }

    const modal = document.getElementById('color-modal');
    if (modal) {
      modal.classList.add('hidden');
    }
    currentColumnId = null;
    selectedColor = null;

    const preview = document.getElementById('color-preview');
    if (preview) {
      preview.classList.add('hidden');
    }

    document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
    });
    }

    document.addEventListener('DOMContentLoaded', function () {

    const closeBtn = document.getElementById('close-color-modal');
    const cancelBtn = document.getElementById('cancel-color-btn');
    const modal = document.getElementById('color-modal');
    const applyBtn = document.getElementById('apply-color-btn');

    if (closeBtn) {
      closeBtn.onclick = function () {
      const modal = document.getElementById('color-modal');
      if (modal) {
      modal.classList.add('hidden');
      }
      currentColumnId = null;
      selectedColor = null;
      document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
      });
      };
    }

    if (cancelBtn) {
      cancelBtn.onclick = function () {
      const modal = document.getElementById('color-modal');
      if (modal) {
      modal.classList.add('hidden');
      }
      currentColumnId = null;
      selectedColor = null;
      document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
      });
      };
    }

    if (modal) {
      modal.onclick = function (e) {
      if (e.target.id === 'color-modal') {
      const modal = document.getElementById('color-modal');
      if (modal) {
      modal.classList.add('hidden');
      }
      currentColumnId = null;
      selectedColor = null;
      document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
      });
      }
      };
    }

    const customColorPicker = document.getElementById('custom-color-picker');
    const applyCustomColorBtn = document.getElementById('apply-custom-color');

    if (customColorPicker && applyCustomColorBtn) {
      applyCustomColorBtn.onclick = function () {
      const customColor = customColorPicker.value;

      document.querySelectorAll('.color-option').forEach(opt => {
      opt.classList.remove('ring-4', 'ring-blue-300');
      });

      selectedColor = customColor;
      showColorPreview(customColor, 'Personnalisée');
      };
    }

    if (applyBtn) {
      applyBtn.onclick = function () {
      if (selectedColor && currentColumnId) {
      applyColumnColor(currentColumnId, selectedColor);
      }
      };
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
      const modal = document.getElementById('color-modal');
      if (modal) {
      modal.classList.add('hidden');
      }
      currentColumnId = null;
      selectedColor = null;
      document.querySelectorAll('.column-menu').forEach(menu => {
      menu.classList.add('hidden');
      });
      }
    });


    applySavedColors();
    });

    function applySavedColors() {
    document.querySelectorAll('[data-list-task-id]').forEach(column => {
      const savedColor = column.getAttribute('data-color');
      if (savedColor) {

      column.className = column.className.replace(/border-\w+-\d+/g, '');
      column.className = column.className.replace(/bg-\w+-\d+/g, '');
      column.className = column.className.replace(/dark:border-\w+-\d+/g, '');
      column.className = column.className.replace(/dark:bg-\w+-\d+/g, '');

      column.style.borderColor = '';
      column.style.backgroundColor = '';

      if (savedColor.startsWith('#')) {
      column.style.borderColor = savedColor;
      column.style.backgroundColor = savedColor + '20';
      } else if (savedColor) {
      column.classList.add(`border-${savedColor}-400`, `dark:border-${savedColor}-500`);
      column.classList.add(`bg-${savedColor}-50`, `dark:bg-${savedColor}-900/20`);
      }
      }
    });
    }
    </script>
  @endpush
@endonce

<div id="task-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
  <div class="flex items-center justify-center min-h-screen p-2">
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-visible transform transition-all duration-300 scale-95 opacity-0"
      id="task-modal-content">
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2">
          <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
          <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Détails de la tâche</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Modifiez et gérez votre tâche</p>
          </div>
        </div>
        <button id="close-task-modal"
          class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-all duration-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="flex gap-4 p-6">
        <!-- Main Content -->
        <div class="flex-1 pr-3 space-y-4">
          <!-- Title -->
          <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm p-4 space-y-4">
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-heading text-blue-400 text-xs"></i> Titre
              </label>
              <input type="text" id="task-title"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                placeholder="Titre de la tâche">
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-align-left text-blue-400 text-xs"></i> Description
              </label>
              <textarea id="task-description" rows="3"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm resize-none"
                placeholder="Décrivez votre tâche en détail..."></textarea>
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-tags text-blue-400 text-xs"></i> Tags
              </label>
              <div class="flex flex-wrap gap-2 mb-2 min-h-[24px]" id="tags-container">
                <!-- Tags will be added here -->
              </div>
              <div class="flex gap-2">
                <input type="text" id="new-tag"
                  class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Ajouter un tag...">
                <button id="add-tag"
                  class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                  <i class="fas fa-plus"></i>Ajouter
                </button>
              </div>
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-comments text-blue-400 text-xs"></i> Commentaires
              </label>
              <div id="comments-container" class="space-y-1 mb-2 max-h-32 overflow-y-auto pr-1">
              </div>
              <div class="flex gap-2 mt-2">
                <textarea id="new-comment" rows="1"
                  class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                  placeholder="Ajouter un commentaire..."></textarea>
                <button id="add-comment"
                  class="px-3 py-1 bg-green-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                  <i class="fas fa-paper-plane"></i>Envoyer
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="w-64 flex-shrink-0 space-y-4">
          <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm p-4 space-y-3">
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-columns text-blue-400 text-xs"></i> Statut
              </label>
              <select id="task-status"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                @foreach($projet->listTasks ?? [] as $listTask)
          <option value="{{ $listTask->id }}">{{ $listTask->title }}</option>
        @endforeach
              </select>
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-flag text-blue-400 text-xs"></i> Priorité
              </label>
              <select id="task-priority"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <option value="">Aucune</option>
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="élevée">Élevée</option>
              </select>
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-folder text-blue-400 text-xs"></i> Catégorie
              </label>
              <select id="task-category"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <option value="">Aucune</option>
                <option value="marketing">Marketing</option>
                <option value="développement">Développement</option>
                <option value="communication">Communication</option>
              </select>
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-calendar-alt text-blue-400 text-xs"></i> Date limite
              </label>
              <input type="date" id="task-due-date"
                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>
            <div>
              <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                <i class="fas fa-users text-blue-400 text-xs"></i> Assignés
              </label>
              <div id="assignees-container" class="space-y-2 mb-2 min-h-[24px]">
                <!-- Current assignees will be shown here -->
              </div>
              <div class="flex gap-2">
                <select id="assignee-select"
                  class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                  <option value="">Sélectionner un membre...</option>
                  @foreach($projet->members ?? [] as $membre)
            <option value="{{ $membre->id }}">{{ $membre->name }}</option>
          @endforeach
                </select>
                <button id="add-assignee"
                  class="px-3 py-1 bg-green-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-2 pt-2">
            <button id="save-task"
              class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-sm text-sm flex items-center justify-center gap-1">
              <i class="fas fa-save"></i>Sauvegarder
            </button>
            <button id="delete-task"
              class="w-full px-3 py-2 bg-red-600 hover:bg-pink-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-sm text-sm flex items-center justify-center gap-1">
              <i class="fas fa-trash"></i>Supprimer la tâche
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal moderne de sélection de couleur pour les listes -->
<div id="color-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-bold text-gray-900 dark:text-white">Choisir une couleur</h3>
      <button id="close-color-modal"
        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl">
        <i class="fas fa-times"></i>
      </button>
    </div>


    <!-- Prévisualisation de la couleur sélectionnée -->
    <div id="color-preview"
      class="mb-6 p-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hidden">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div id="preview-color-box" class="w-8 h-8 rounded-lg"></div>
          <div>
            <span id="preview-color-name" class="font-medium text-gray-900 dark:text-white"></span>
            <p class="text-sm text-gray-600 dark:text-gray-400">Prévisualisation de la couleur</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Color picker personnalisé -->
    <div class="mb-6">
      <div class="flex items-center space-x-3 mb-3">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Couleur personnalisée :</span>
        <input type="color" id="custom-color-picker"
          class="w-12 h-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer" value="#3b82f6">
      </div>
      <button id="apply-custom-color"
        class="w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 font-medium">
        <i class="fas fa-palette mr-2"></i>Appliquer la couleur personnalisée
      </button>
    </div>
    <div class="flex justify-end space-x-3">
      <button id="cancel-color-btn"
        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors font-medium">
        Annuler
      </button>
      <button id="apply-color-btn"
        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
        Appliquer
      </button>
    </div>
  </div>
</div>

<style>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .task-card {
    position: relative;
    overflow: hidden;
  }

  .task-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .task-card:hover::before {
    opacity: 1;
  }

  .priority-badge {
    position: relative;
  }

  .priority-badge::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.8;
  }

  /* Animation pour les cartes */
  .task-card {
    animation: slideIn 0.3s ease-out;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(20px) scale(0.95);
    }

    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  /* Styles pour les conteneurs vides */
  .empty-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: #9ca3af;
  }

  .empty-container i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
  }

  .empty-state {
    cursor: default !important;
    user-select: none;
    pointer-events: auto;
  }

  .empty-state button {
    pointer-events: auto;
    cursor: pointer;
  }
</style>