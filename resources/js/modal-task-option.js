// Sélectionne le parent commun (par exemple, la liste qui contient toutes les tâches)
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

            const deleteBtn = containerTask.querySelector('.button-delete-task');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function () {
                    fetch(`/task/delete/${deleteBtn.value}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                const taskElement = document.getElementById(`task-${deleteBtn.value}`);
                                if (taskElement) {
                                    taskElement.remove();
                                }
                            } else {
                                console.error('Erreur lors de la suppression de la tâche');
                            }
                            return response.json();
                        }).catch(error => {
                            console.error('Error:', error);
                        });
                })
            }
        }

    });
}

