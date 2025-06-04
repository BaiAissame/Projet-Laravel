document.getElementById('form-create-task').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent the default form submission
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
      document.getElementById('taskes-list').insertAdjacentHTML('beforeend', data
        .html);
      input.value = ''; // Clear the input field after successful submission

    }).catch(error => {
      console.error('Erreur:', error);
    });
});

function makeTasksDraggable() {
  document.querySelectorAll('#taskes-list > li').forEach(li => {
    li.setAttribute('draggable', true);

    li.addEventListener('dragstart', function (e) {
      li.classList.add('opacity-50');
      window.draggedLi = li;

    });

    li.addEventListener('dragend', function () {
      li.classList.remove('opacity-50');
      window.draggedLi = null;
    });
  });
}

// Initialiser le drag and drop
makeTasksDraggable();

// Limiter la fréquence d'envoi des mises à jour de l'ordre
const throttledSendOrderUpdate = throttle(sendOrderUpdate, 100);

// Gérer le dragover sur la liste
document.getElementById('taskes-list').addEventListener('dragover', function (e) {
  e.preventDefault();
  const afterElement = getDragAfterElement(this, e.clientX);
  const dragged = window.draggedLi;
  const newOrder = Array.from(this.children).indexOf(dragged) + 1
  if (!dragged) return;
  if (afterElement == null) {
    this.appendChild(dragged);
    throttledSendOrderUpdate(dragged.dataset.taskId, dragged.dataset.taskOrder, newOrder, this.dataset
      .projetId);
  } else {
    this.insertBefore(dragged, afterElement);
    if (dragged.dataset.taskOrder == newOrder) return;
    throttledSendOrderUpdate(dragged.dataset.taskId, dragged.dataset.taskOrder, newOrder, this.dataset
      .projetId);
  }
});

// Trouver l'élément après lequel insérer
function getDragAfterElement(container, x) {
  const draggableElements = [...container.querySelectorAll('li:not(.opacity-50)')];
  let closest = {
    offset: Number.POSITIVE_INFINITY,
    element: null
  };
  draggableElements.forEach(child => {
    const box = child.getBoundingClientRect();
    const offset = x - box.left - box.width / 2;
    if (offset < 0 && Math.abs(offset) < Math.abs(closest.offset)) {
      closest = {
        offset: offset,
        element: child
      };
    }
  });
  return closest.element;
}

// Fonction de throttling pour limiter la fréquence d'exécution
function throttle(fn, delay) {
  let lastCall = 0;
  return function (...args) {
    const now = Date.now();
    if (now - lastCall >= delay) {
      lastCall = now;
      fn.apply(this, args);
    }
  };
}

// Fonction pour envoyer la mise à jour de l'ordre des tâches
function sendOrderUpdate(taskId, order, newOrder, projetId) {
  fetch('/task/update-order', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
        'content')
    },
    body: JSON.stringify({
      taskId: taskId,
      order: order,
      newOrder: newOrder,
      projetId: projetId,
    })
  }).then(response => response.json())
    .then(data => {
      console.log('Order updated:', data);
    }).catch(error => {
      console.error('Error updating order:', error);
    });
}