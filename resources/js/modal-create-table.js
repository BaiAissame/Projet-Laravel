const modalCreateTable = document.getElementById('modal-create-table');
const buttonCreateTable = document.getElementsByClassName('nav-create-table');
const app = document.getElementById('app');

for (let i = 0; i < buttonCreateTable.length; i++) {
    buttonCreateTable[i].addEventListener('click', function () {
        modalCreateTable.classList.replace('hidden', 'absolute');
    })
}

if (app) {
    document.getElementById('close-modal-create-table').addEventListener('click', function () {
        modalCreateTable.classList.replace('absolute', 'hidden');
    })

    document.addEventListener('click', function (event) {
        if (!modalCreateTable.contains(event.target) && !event.target.classList.contains('nav-create-table')) {
            modalCreateTable.classList.replace('absolute', 'hidden');
        }
    })
}




