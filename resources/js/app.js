import './bootstrap';
// import './modal';
import './listTask';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Fonction de notification moderne
window.showNotification = function(title, message, type = 'info') {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());

    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transform transition-all duration-300 translate-x-full`;
    
    // Couleurs selon le type
    const colors = {
        success: 'border-green-500 bg-green-50 dark:bg-green-900/20',
        error: 'border-red-500 bg-red-50 dark:bg-red-900/20',
        warning: 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20',
        info: 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
    };

    const icons = {
        success: `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>`,
        error: `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>`,
        warning: `<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>`,
        info: `<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>`
    };

    notification.className += ` ${colors[type] || colors.info}`;
    
    notification.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${icons[type] || icons.info}
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        ${title}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        ${message}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;

    // Ajouter au DOM
    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
};

// Fonctions pour la gestion des listes dans la page projet/{nomprojet}

// Initialisation des menus d'options des colonnes
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des menus d'options des colonnes
    document.addEventListener('click', function(e) {
        if (e.target.closest('.column-menu-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.column-menu-btn');
            const columnId = btn.getAttribute('data-column-id');
            const menu = btn.nextElementSibling;
            
            // Fermer tous les autres menus
            document.querySelectorAll('.column-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            
            // Toggle du menu actuel
            menu.classList.toggle('hidden');
        } else if (!e.target.closest('.column-menu')) {
            // Fermer tous les menus si on clique ailleurs
            document.querySelectorAll('.column-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // Gestion du changement de couleur
    document.addEventListener('click', function(e) {
        if (e.target.closest('.color-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.color-btn');
            const columnId = btn.getAttribute('data-column-id');
            openColorModal(columnId);
        }
    });
});

// Modal de sélection de couleur
function openColorModal(columnId) {
    // Supprimer l'ancien modal s'il existe
    const existingModal = document.getElementById('color-modal');
    if (existingModal) existingModal.remove();

    const colors = [
        { name: 'Bleu', class: 'bg-blue-500', value: 'blue' },
        { name: 'Vert', class: 'bg-green-500', value: 'green' },
        { name: 'Rouge', class: 'bg-red-500', value: 'red' },
        { name: 'Jaune', class: 'bg-yellow-500', value: 'yellow' },
        { name: 'Violet', class: 'bg-purple-500', value: 'purple' },
        { name: 'Rose', class: 'bg-pink-500', value: 'pink' },
        { name: 'Orange', class: 'bg-orange-500', value: 'orange' },
        { name: 'Indigo', class: 'bg-indigo-500', value: 'indigo' },
        { name: 'Teal', class: 'bg-teal-500', value: 'teal' },
        { name: 'Gray', class: 'bg-gray-500', value: 'gray' }
    ];

    const modal = document.createElement('div');
    modal.id = 'color-modal';
    modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="color-modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Choisir une couleur</h3>
                    <button onclick="closeColorModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="grid grid-cols-5 gap-3">
                    ${colors.map(color => `
                        <button onclick="changeListColor('${columnId}', '${color.value}')" 
                                class="w-12 h-12 ${color.class} rounded-lg hover:scale-110 transition-all duration-200 shadow-lg hover:shadow-xl"
                                title="${color.name}">
                        </button>
                    `).join('')}
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Animation d'entrée
    setTimeout(() => {
        const content = document.getElementById('color-modal-content');
        content.classList.remove('scale-95', 'opacity-0');
    }, 100);
}

// Fermer le modal de couleur
window.closeColorModal = function() {
    const modal = document.getElementById('color-modal');
    if (modal) {
        const content = document.getElementById('color-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.remove(), 300);
    }
};

// Changer la couleur d'une liste
window.changeListColor = function(columnId, color) {
    fetch('/listTask/change-color', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            listTaskId: columnId,
            color: color
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Appliquer la couleur à la liste
            const listElement = document.querySelector(`[data-list-id="${columnId}"]`);
            if (listElement) {
                // Supprimer les anciennes classes de couleur
                const colorClasses = ['bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 
                                     'bg-purple-500', 'bg-pink-500', 'bg-orange-500', 'bg-indigo-500', 
                                     'bg-teal-500', 'bg-gray-500'];
                colorClasses.forEach(cls => listElement.classList.remove(cls));
                
                // Ajouter la nouvelle couleur
                listElement.classList.add(`bg-${color}-500`);
                
                // Mettre à jour l'attribut data-color
                listElement.setAttribute('data-color', color);
            }
            
            closeColorModal();
            showNotification('Succès', 'Couleur de la liste mise à jour', 'success');
        } else {
            showNotification('Erreur', data.message || 'Erreur lors du changement de couleur', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur', 'Erreur lors du changement de couleur', 'error');
    });
};

// Éditer le nom d'une colonne
window.editColumnName = function(columnId) {
    const listElement = document.querySelector(`[data-list-id="${columnId}"]`);
    const input = listElement.querySelector('input[readonly]');
    const currentValue = input.value;
    
    // Rendre l'input éditable
    input.removeAttribute('readonly');
    input.focus();
    input.select();
    
    // Gérer la sauvegarde
    input.addEventListener('blur', function() {
        saveColumnName(columnId, input.value);
    });
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            saveColumnName(columnId, input.value);
        }
    });
};

// Sauvegarder le nom d'une colonne
function saveColumnName(columnId, newName) {
    fetch('/listTask/update-title', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            listTaskId: columnId,
            title: newName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const input = document.querySelector(`[data-list-id="${columnId}"] input[readonly]`);
            input.setAttribute('readonly', '');
            showNotification('Succès', 'Nom de la liste mis à jour', 'success');
        } else {
            showNotification('Erreur', data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur', 'Erreur lors de la mise à jour', 'error');
    });
}

