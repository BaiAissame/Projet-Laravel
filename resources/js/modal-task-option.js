import { showErrorModal } from './modal';
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
            // Sinon, on toggle la modal
            const modal = containerTask.querySelector('.container-modal-task-option');
            if (modal) {
                modal.classList.toggle('hidden');
            }

            //supprimer la tâche
            containerTask.querySelector('.button-delete-task').addEventListener('click', function () {
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

            // rejoindre la tâche
            containerTask.querySelector('.button-join-task').addEventListener('click', function () {
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
                            console.log("object");
                        } else {
                            showErrorModal('Un problème technique est survenu, veuillez réessayer plus tard'); // Affiche le modal temporaire
                        }
                        return response.json();
                    }).catch(error => {
                        console.error('Error:', error);
                    });
            });
        }

        containerTask.querySelector('[name="task-category"]').addEventListener('change', function () {
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

        containerTask.querySelector('[name="task-priority"]').addEventListener('change', function () {
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

    });
}

