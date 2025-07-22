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
    notification.className = `notification-toast fixed top-20 z-50 max-w-xs w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transform transition-all duration-300 translate-x-full`;

    // Couleurs selon le type
    const colors = {
        success: 'border-green-500 bg-gradient-to-r from-emerald-500 to-green-600 text-white',
        error: 'border-red-500 bg-gradient-to-r from-red-500 to-pink-600 text-white',
        warning: 'border-yellow-500 bg-gradient-to-r from-amber-500 to-orange-600 text-white',
        info: 'border-blue-500 bg-gradient-to-r from-blue-500 to-purple-600 text-white'
    };

    const icons = {
        success: `<i class="fas fa-check-circle text-sm text-white"></i>`,
        error: `<i class="fas fa-times-circle text-sm text-white"></i>`,
        warning: `<i class="fas fa-exclamation-triangle text-sm text-white"></i>`,
        info: `<i class="fas fa-info-circle text-sm text-white"></i>`
    };

    notification.className += ` ${colors[type] || colors.info}`;

    notification.innerHTML = `
        <div class="p-3">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-2">
                    ${icons[type] || icons.info}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-xs text-white">${title}</div>
                    <div class="text-xs text-white/80 mt-0.5 leading-tight">${message}</div>
                </div>
                <button class="ml-2 text-white/70 hover:text-white transition-colors flex-shrink-0" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    `;

    // Ajouter au DOM
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed !important;
            top: 85px !important;
            right: 200px !important;
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            pointer-events: none !important;
            max-width: 400px !important;
        `;
        document.body.appendChild(container);
    } else {
        // S'assurer que le container existant est bien positionné
        container.style.cssText = `
            position: fixed !important;
            top: 85px !important;
            right: 200px !important;
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            pointer-events: none !important;
            max-width: 400px !important;
        `;
    }

    // Ajouter la notification au container
    container.appendChild(notification);

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
        showNotification('Erreur', 'Erreur lors de la mise à jour', 'error');
    });
}

// Fonction d'affichage d'une erreur (issue de modal.js)
window.showErrorModal = function(message) {
    let modal = document.getElementById('custom-error-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'custom-error-modal';
        modal.style.position = 'fixed';
        modal.style.top = '20px';
        modal.style.left = '50%';
        modal.style.transform = 'translateX(-50%)';
        modal.style.background = '#f87171';
        modal.style.color = '#fff';
        modal.style.padding = '16px 32px';
        modal.style.borderRadius = '8px';
        modal.style.zIndex = '9999';
        modal.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
        document.body.appendChild(modal);
    }
    modal.textContent = message;
    modal.style.display = 'block';

    setTimeout(() => {
        modal.style.display = 'none';
    }, 3000);
};

