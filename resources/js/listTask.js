
let draggedList = null;
let draggedTask = null;
let originalListIndex = null;
let originalTaskIndex = null;
let originalTaskParent = null;

const taskList = document.getElementById('taskes-list');

if (taskList) {

  const formCreateListTask = document.getElementById('form-create-list-task');

  initlistTask()

  initTask();

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

        taskList.insertAdjacentHTML('beforeend', data.html);

        input.value = '';

        initlistTask()

      }).catch(error => {
      });
  });


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
      });
  }



  function initlistTask() {

    document.querySelectorAll('.list-menu-trigger').forEach(trigger => {
      if (!trigger._hasMenuListener) {
        trigger.addEventListener('click', function (e) {
          e.stopPropagation();
          const menu = this.parentElement.querySelector('.list-menu');

          document.querySelectorAll('.list-menu').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
          });

          menu.classList.toggle('hidden');
        });
        trigger._hasMenuListener = true;
      }
    });

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
                    // Ferme tous les menus d'options
                    document.querySelectorAll('.list-menu').forEach(m => m.classList.add('hidden'));
                    if (typeof showNotification === 'function') {
                      showNotification('Succès', 'Liste supprimée avec succès', 'success');
                    }
                  }
                }
              })
              .catch(error => {
              });
          }
        });
        btn._hasDeleteListener = true;
      }
    });

    document.querySelectorAll('.list-task').forEach(list => {

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
              }).catch(error => {
              });
          }
        });
        list._hasDropListener = true;
      }
    });

    document.querySelectorAll('input[name="list-title"]').forEach(input => {

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
            });
        });
      }
    });

    document.querySelectorAll('.form-create-task').forEach(formTask => {

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

        formTask._hasSubmitListener = true;
      }
    });

  }

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

  if (!taskList._hasDragEndListener) {
    taskList.addEventListener('dragend', function () {
      draggedList = null;
    });
    taskList._hasDragEndListener = true;
  }

  if (!taskList._hasClickListener) {
    document.addEventListener('click', function () {
      document.querySelectorAll('.list-menu').forEach(menu => {
        menu.classList.add('hidden');
      });
    });
    taskList._hasClickListener = true;
  }

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

  function getDragAfterElementGeneric(container, coord, axis = 'x', selector) {
    const draggableElements = [...container.querySelectorAll(selector)];
    let closest = { offset: Number.POSITIVE_INFINITY, element: null };
    draggableElements.forEach(child => {
      const box = child.getBoundingClientRect();
      const offset = axis === 'x'
        ? coord - box.left - box.width / 2
        : coord - box.top - box.height / 2;
      if (offset < 0 && Math.abs(offset) < Math.abs(closest.offset)) {
        closest = { offset: offset, element: child };
      }
    });
    return closest.element;
  }

  function getDragAfterElement(container, x) {
    return getDragAfterElementGeneric(container, x, 'x', '.list-task:not(.opacity-50)');
  }
  function getDragAfterElementTask(container, y) {
    return getDragAfterElementGeneric(container, y, 'y', '.container-task:not(.opacity-50)');
  }

}

const board = document.querySelector('#kanban-board .flex');
if (board && window.Sortable) {
  new Sortable(board, {
    animation: 200,
    handle: '.list-handle',
    draggable: '[data-list-id]',
    onStart: function (evt) {
      draggedList = evt.item;
      originalListIndex = evt.oldIndex;
      evt.item.classList.add('dragging-list');
    },
    onEnd: function (evt) {
      evt.item.classList.remove('dragging-list');
      document.querySelectorAll('.drop-highlight').forEach(el => {
        el.classList.remove('drop-highlight');
      });
      if (evt.to !== board) {
        if (originalListIndex !== null) {
          board.insertBefore(evt.item, board.children[originalListIndex]);
        }
      } else {
        const orderList = Array.from(board.querySelectorAll('[data-list-id]')).map((el, idx) => ({
          listTaskId: el.getAttribute('data-list-id'),
          order: idx + 1
        }));
        fetch('/listTask/update-order', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ orderList })
        });
      }
      draggedList = null;
      originalListIndex = null;
    },
    onMove: function (evt) {
    }
  });
}

Array.from(document.querySelectorAll('[data-list-id] .flex-1')).forEach(list => {
  if (window.Sortable) {
    new Sortable(list, {
      group: 'tasks',
      animation: 200,
      draggable: '.bg-blue-50, .dark\\:bg-blue-900',
      onStart: function (evt) {
        draggedTask = evt.item;
        originalTaskIndex = evt.oldIndex;
        originalTaskParent = evt.from;
        evt.item.classList.add('dragging-task');
      },
      onEnd: function (evt) {
        evt.item.classList.remove('dragging-task');
        document.querySelectorAll('.drop-highlight').forEach(el => {
          el.classList.remove('drop-highlight');
        });
        if (!evt.to || !evt.to.closest('[data-list-id]')) {
          if (originalTaskParent && originalTaskIndex !== null) {
            originalTaskParent.insertBefore(evt.item, originalTaskParent.children[originalTaskIndex]);
          }
        } else {
          const parentListId = evt.to.closest('[data-list-id]').getAttribute('data-list-id');
          const orderTask = Array.from(evt.to.children).map((el, idx) => ({
            taskId: el.getAttribute('onclick').match(/openTaskModal\((\d+)\)/)[1],
            order: idx + 1,
            listTaskId: parentListId
          }));
          fetch('/task/update-order', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ orderTask })
          });
        }
        draggedTask = null;
        originalTaskIndex = null;
        originalTaskParent = null;
      },
      onMove: function (evt) {
      }
    });
  }
});

const style = document.createElement('style');
style.innerHTML = `
.dragging-list { opacity: 0.7; box-shadow: 0 0 0 2px #3b82f6; }
.dragging-task { opacity: 0.7; box-shadow: 0 0 0 2px #6366f1; }
`;
document.head.appendChild(style);


const style2 = document.createElement('style');
style2.innerHTML = `
[data-list-id].drop-hover { box-shadow: 0 0 0 3px #a5b4fc; }
`;
document.head.appendChild(style2);
Array.from(document.querySelectorAll('[data-list-id]')).forEach(list => {
  list.addEventListener('dragenter', function() { this.classList.add('drop-hover'); });
  list.addEventListener('dragleave', function() { this.classList.remove('drop-hover'); });
  list.addEventListener('drop', function() { this.classList.remove('drop-hover'); });
});
document.addEventListener('dragend', function() {
  document.querySelectorAll('.drop-highlight').forEach(el => {
    el.classList.remove('drop-highlight');
  });
  draggedList = null;
  draggedTask = null;
  originalListIndex = null;
  originalTaskIndex = null;
  originalTaskParent = null;
});




