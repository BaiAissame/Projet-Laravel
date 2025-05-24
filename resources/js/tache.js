const buttonCreateCard = document.getElementsByClassName('button-create-card');
const html = "<p>coucou</p>"

for (let i = 0; i < buttonCreateCard.length; i++) {
    buttonCreateCard[i].addEventListener('click', function () {
      this.parentElement.insertBefore(html, this);
    })
}