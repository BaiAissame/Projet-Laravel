import { showErrorModal, saveTaskChanges } from './modal';
const taskList = document.getElementById('taskes-list');
if (taskList) {
    taskList.addEventListener('click', function (event) {
        // Vérifie si le clic vient d'un .container-task ou d'un de ses enfants
        const containerTask = event.target.closest('.container-task');

        if (containerTask && taskList.contains(containerTask)) {
            // Si le clic vient d'un .modal-task-option, on stoppe la propagation
            if (event.target.closest('.modal-task-option')) {
                event.stopPropagation();
                return;
            }
            const modal = containerTask.querySelector('.container-modal-task-option');
            // Sinon, on toggle la modal
            if (modal) {
                modal.classList.toggle('hidden');
                if (modal.classList.contains('hidden')) saveTaskChanges(modal);

            }

            //supprimer la tâche
            const buttonDeleteTask = containerTask.querySelector('.button-delete-task');
            if (!buttonDeleteTask._hasDeleteListener) {
                buttonDeleteTask.addEventListener('click', function () {
                    fetch(`/task/delete/${this.value}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                showErrorModal(data.message);
                                return;
                            } else {
                                const taskElement = document.querySelector(`[data-task-id="${this.value}"]`);
                                if (taskElement) {
                                    taskElement.remove();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                })
                buttonDeleteTask._hasDeleteListener = true;
            }

            // rejoindre la tâche
            const buttonJoinTask = containerTask.querySelector('.button-join-task');
            if (!buttonJoinTask._hasJoinListener) {
                buttonJoinTask.addEventListener('click', function () {
                    fetch(`/task/${this.dataset.state}/${this.value}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                if (this.dataset.state === "join") {
                                    this.innerHTML = "<i class='fas fa-door-open mr-1'></i> Quitter" // Désactive le bouton après la jointure
                                    this.dataset.state = "leave"; // Change l'état du bouton
                                } else {
                                    this.innerHTML = "<i class='fas fa-user-plus mr-1'></i> Rejoindre"; // Réactive le bouton après la sortie
                                    this.dataset.state = "join"; // Change l'état du bouton
                                }
                            } else {
                                showErrorModal('Un problème technique est survenu, veuillez réessayer plus tard'); // Affiche le modal temporaire
                            }
                            return response.json();
                        }).catch(error => {
                            console.error('Error:', error);
                        });
                });
                buttonJoinTask._hasJoinListener = true;
            }

            const selectCategory = containerTask.querySelector('[name="task-category"]');
            if (!selectCategory._hasCloseListener) {
                selectCategory.addEventListener('change', function () {
                    fetch(`/task/update-category/${this.dataset.taskId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ category: this.value })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                showErrorModal(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
                selectCategory._hasCloseListener = true;
            }

            const selectPriority = containerTask.querySelector('[name="task-priority"]');
            if (!selectPriority._hasCloseListener) {
                selectPriority.addEventListener('change', function () {
                    fetch(`/task/update-priority/${this.dataset.taskId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ priority: this.value })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                showErrorModal(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
                selectPriority._hasCloseListener = true;
            }

            const closeBtn = containerTask.querySelector('#close-modal-task');
            if (!closeBtn._hasCloseListener) {
                closeBtn.addEventListener('click', function () {
                    const modal = containerTask.querySelector('.container-modal-task-option');
                    saveTaskChanges(modal);
                    modal.classList.toggle('hidden');
                });
                closeBtn._hasCloseListener = true;
            }
        }
    });
}

