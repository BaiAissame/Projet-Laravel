const modalCreateTable = document.getElementById('modal-create-table');

document.getElementById('nav-create-table').addEventListener('click', function() {
    modalCreateTable.classList.replace('hidden', 'absolute');  
})

document.getElementById('close-modal-create-table').addEventListener('click', function() {
    modalCreateTable.classList.replace('absolute', 'hidden');
})

document.addEventListener('click', function (event) {
    if (!modalCreateTable.contains(event.target) && event.target.id !== 'nav-create-table') {
        modalCreateTable.classList.replace('absolute', 'hidden');
      }
})