
const taskList = document.getElementById('taskes-list');

// Vérifie si le formulaire et la liste des tâches existent avant d'ajouter l'écouteur d'événements
if (taskList) {

  const formCreateListTask = document.getElementById('form-create-list-task');

  // Initialisation des listes pour heriter les événements de drag and drop et creation de tâches
  initlistTask()

  // Initialisation des tâches pour les evénements de drag and drop
  initTask();

  //formulaire pour créer une nouvelle liste de tâches 
  formCreateListTask.addEventListener('submit', function (e) {
    e.preventDefault();

    const input = this.querySelector('.title-task');

    if (input.value.trim() === '') {
      alert('Veuillez entrer un titre pour la tâche.');
      return;
    }

    fetch(this.action, {
      method: 'POST',
      body: new FormData(this),
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
          'content')
      }
    })
      .then(response => response.json())
      .then(data => {

        // ajouter la nouvelle liste de tâches à la liste existante, composant HTML
        taskList.insertAdjacentHTML('beforeend', data.html);

        // Réinitialise le champ de saisie après l'ajout de la liste
        input.value = '';

        // Réinitialise les événements pour les nouvelles listes et tâches
        initlistTask()

      }).catch(error => {
        console.error('Erreur:', error);
      });
  });


  // Fonction pour envoyer la mise à jour de l'ordre des tâches
  function sendOrderUpdateList(orderList) {
    fetch('/listTask/update-order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
          'content')
      },
      body: JSON.stringify({ orderList: orderList })
    }).then(response => response.json())
      .then(data => {
      }).catch(error => {
        console.error('Error updating order:', error);
      });
  }


  // ############## LISTES #######################

  // Initialisation des listes pour heriter les événements de drag and drop et creation de tâches
  function initlistTask() {

    // Gérer le clic sur le trigger du menu => ... sur la liste
    document.querySelectorAll('.list-menu-trigger').forEach(trigger => {
      if (!trigger._hasMenuListener) {
        trigger.addEventListener('click', function (e) {
          e.stopPropagation();
          const menu = this.parentElement.querySelector('.list-menu');

          // Ferme tous les autres menus
          document.querySelectorAll('.list-menu').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
          });

          // Toggle ce menu
          menu.classList.toggle('hidden');
        });
        trigger._hasMenuListener = true;
      }
    });

    // Gérer le clic sur "Supprimer"
    document.querySelectorAll('.delete-list-btn').forEach(btn => {
      if (!btn._hasDeleteListener) {
        btn.addEventListener('click', function () {
          const listTaskId = this.dataset.listTaskId;

          if (confirm('Êtes-vous sûr de vouloir supprimer cette liste ?')) {
            fetch(`/listTask/delete/${listTaskId}`, {
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
                } else {
                  // Supprime la liste du DOM
                  const listElement = document.querySelector(`[data-list-task-id="${listTaskId}"]`);
                  if (listElement) {
                    listElement.remove();
                  }
                }
              })
              .catch(error => {
                console.error('Error:', error);
              });
          }
        });
        btn._hasDeleteListener = true;
      }
    });

    document.querySelectorAll('.list-task').forEach(list => {

      // évite d'ajouter plusieurs fois le même listener
      if (!list._hasDragStartListener) {

        list.addEventListener('dragstart', function (e) {
          if (e.target.classList.contains('list-task')) {
            draggedList = this;
            draggedTask = null;
          }
        });
        list._hasDragStartListener = true;

      }

      if (!list._hasDragOverListener) {
        list.addEventListener('dragover', function (e) {
          if (draggedTask) {
            e.preventDefault();
          }
        });
        list._hasDragOverListener = true;
      }

      if (!list._hasDropListener) {
        list.addEventListener('drop', function (e) {
          if (draggedTask) {
            e.preventDefault();
            const ol = this.querySelector('ol');
            if (ol && ol.children.length === 0) {

              ol.appendChild(draggedTask);
              // Optionnel : fetch pour update la liste et l'ordre côté serveur
            }
            draggedTask = null;
            const orderTask = Array.from(this.querySelectorAll('ol > li ')).map((task, idx) => ({
              taskId: task.dataset.taskId,
              listTaskId: this.dataset.listTaskId,
              order: idx + 1
            }));

            fetch('/task/update-order', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                  'content')
              },
              body: JSON.stringify({ orderTask: orderTask })
            }).then(response => response.json())
              .then(data => {
                // console.log(data);
              }).catch(error => {
                console.error('Error updating order:', error);
              });
          }
        });
        list._hasDropListener = true;
      }
    });

    document.querySelectorAll('input[name="list-title"]').forEach(input => {

      // évite d'ajouter plusieurs fois le même listener
      if (!input._hasBlurListener) {
        input.addEventListener('blur', function () {
          const listTaskId = this.dataset.listTaskId;
          const title = this.value;
          fetch(`/listTask/update-title/${listTaskId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ title })
          })
            .then(response => response.json())
            .then(data => {
              if (data.error) {
                showErrorModal(data.message);
              }
            })
            .catch(error => {
              console.error('Erreur lors de la mise à jour du titre de la liste:', error);
            });
        });
      }
    });

    document.querySelectorAll('.form-create-task').forEach(formTask => {

      // évite d'ajouter plusieurs fois le même listener
      if (!formTask._submitListener) {
        formTask._submitListener = function (e) {
          e.preventDefault();
          let titleTask = this.querySelector('input[name="task-title"]');
          if (titleTask.value.trim() === '') {
            alert('Veuillez mettre un titre.');
            return;
          }
          fetch(this.action, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              titleTask: titleTask.value,
              listTaskId: this.dataset.listTaskId
            })
          })
            .then(res => res.json())
            .then(data => {
              titleTask.value = '';
              this.previousElementSibling.insertAdjacentHTML('beforeend', data.html);
              initTask();
            });
        };
        formTask.addEventListener('submit', formTask._submitListener);

        // Marque le formulaire comme ayant un listener pour éviter les doublons
        formTask._hasSubmitListener = true;
      }
    });

  }

  let draggedList = null;
  let draggedTask = null;

  // DRAGOVER pour les listes (déplacement en live)
  if (!taskList._hasDragOverListener) {
    taskList.addEventListener('dragover', function (e) {
      if (draggedList) {
        e.preventDefault();
        const afterElement = getDragAfterElement(this, e.clientX);

        if (afterElement == null) {
          this.appendChild(draggedList);
        } else {
          this.insertBefore(draggedList, afterElement);
        }
      }
    });
    taskList._hasDragOverListener = true;
  }

  if (!taskList._hasDropListener) {
    taskList.addEventListener('drop', function (e) {
      const order = Array.from(this.children).map((li, idx) => ({
        listTaskId: li.dataset.listTaskId,
        order: idx + 1
      }));

      sendOrderUpdateList(order);
    })
    taskList._hasDropListener = true;
  }

  // DRAGEND pour reset la variable
  if (!taskList._hasDragEndListener) {
    taskList.addEventListener('dragend', function () {
      draggedList = null;
    });
    taskList._hasDragEndListener = true;
  }

  // Fermer le menu si on clique ailleurs
  if (!taskList._hasClickListener) {
    document.addEventListener('click', function () {
      document.querySelectorAll('.list-menu').forEach(menu => {
        menu.classList.add('hidden');
      });
    });
    taskList._hasClickListener = true;
  }

  // ############## FIN PARTIE LISTES #######################


  //  ############## TACHES ####################### 

  // Initialisation des tâches pour les evénements de drag and drop
  function initTask() {
    document.querySelectorAll('.container-task').forEach(task => {
      if (!task._hasDragStartListener) {
        task.addEventListener('dragstart', function (e) {
          if (e.target.classList.contains('container-task')) {
            draggedTask = this;
            draggedList = null;
          }
        });
        task._hasDragStartListener = true;
      }
    });


    // DRAGOVER pour les tâches (déplacement en live)
    document.querySelectorAll('.list-task ol').forEach(list => {
      if (!list._hasDragOverListener) {
        list.addEventListener('dragover', function (e) {
          if (draggedTask) {
            e.preventDefault();
            const afterElement = getDragAfterElementTask(this, e.clientY);
            if (afterElement === draggedTask) return;
            if (afterElement == null) {
              this.appendChild(draggedTask);
            } else {
              this.insertBefore(draggedTask, afterElement);
            }
          }
        });
        list._hasDragOverListener = true;
      }

      if (!list._hasDropListener) {
        list.addEventListener('dragend', function () {
          draggedTask = null;
        });
        list._hasDropListener = true;
      }
    });
  }

  // Fonction pour trouver la position d'insertion pour les listes (horizontal)
  function getDragAfterElement(container, x) {
    const draggableElements = [...container.querySelectorAll('.list-task:not(.opacity-50)')];
    let closest = { offset: Number.POSITIVE_INFINITY, element: null };
    draggableElements.forEach(child => {
      const box = child.getBoundingClientRect();
      const offset = x - box.left - box.width / 2;
      if (offset < 0 && Math.abs(offset) < Math.abs(closest.offset)) {
        closest = { offset: offset, element: child };
      }
    });
    return closest.element;
  }

  // Fonction pour trouver la position d'insertion pour les tâches (vertical)
  function getDragAfterElementTask(container, y) {
    const draggableElements = [...container.querySelectorAll('.container-task:not(.opacity-50)')];
    let closest = { offset: Number.POSITIVE_INFINITY, element: null };
    draggableElements.forEach(child => {
      const box = child.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;
      if (offset < 0 && Math.abs(offset) < Math.abs(closest.offset)) {
        closest = { offset: offset, element: child };
      }
    });
    return closest.element;
  }

  // ############## FIN PARTIE TACHES #######################
}




