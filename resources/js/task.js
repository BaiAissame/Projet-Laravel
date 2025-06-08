const formCreatTask = document.querySelectorAll('.form-create-task');

if (formCreatTask) {
    formCreatTask.forEach(formTask => {
        formTask.addEventListener('submit', function (e) {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
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
                })
        })
    })
}