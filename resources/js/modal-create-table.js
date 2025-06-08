const modalCreateTable = document.getElementById('modal-create-table');
const buttonCreateTable = document.getElementsByClassName('nav-create-table');
const formCreateTable = document.getElementById('form-create-table');

for (let i = 0; i < buttonCreateTable.length; i++) {
    buttonCreateTable[i].addEventListener('click', function () {
        modalCreateTable.classList.replace('hidden', 'absolute');
    })
}


if (formCreateTable) {
    formCreateTable.addEventListener('click', function () {
        modalCreateTable.classList.replace('absolute', 'hidden');
    })
}

document.addEventListener('click', function (event) {
    if (!modalCreateTable.contains(event.target) && !event.target.classList.contains('nav-create-table')) {
        modalCreateTable.classList.replace('absolute', 'hidden');
    }
})
