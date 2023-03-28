const checkbox = document.querySelectorAll('input[name="cursos[]"]');
const numberField = document.getElementById('cont');
const button = document.getElementById('btn-c')

let count = 0;

checkbox.forEach(function (el) {
    el.addEventListener('change', function () {
        let currentCount = parseInt(numberField.textContent);

        let credits = parseInt(this.parentNode.parentNode.querySelector('#value-courses').textContent);

        if (this.checked) {
            count += credits;
        } else {
            count -= credits;
        }

        currentCount = count;
        numberField.textContent = currentCount;

        if (currentCount >= 10 && currentCount <= 18) {
            button.disabled = false
        } else {
            button.disabled = true
        }

    });
});