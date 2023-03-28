const form = document.querySelector('form');
form.addEventListener('submit', function (event) {
    event.preventDefault();

    const username = document.querySelector('#coduni').value;
    const password = document.querySelector('#passuni').value;
    if (username === '' || password === '') {
        alert('Por favor, completa todos los campos');
        return;
    }

});