document.querySelectorAll('.form-control').forEach(element => {
    element.addEventListener('input', function () {
        if (element.getAttribute('id') == 'name' ||
            element.getAttribute('id') == 'lastname' ||
            element.getAttribute('id') == 'login') {
            if (element.value != '' && element.value.length > 1) {
                element.setAttribute('class', 'form-control is-valid');
            } else {
                element.setAttribute('class', 'form-control is-invalid');
            }
        }
        if (element.getAttribute('id') == 'birthday') {
            let pattern = /^\d{2}-\d{2}-\d{4}$/;
            if (pattern.test(element.value) &&
                Date.parse(element.value) < Date.now()
            ) {
                element.setAttribute('class', 'form-control is-valid');
            } else {
                element.setAttribute('class', 'form-control is-invalid');
            }
        }
        if (element.getAttribute('id') == 'password') {
            let pattern = /^(.*[a-zA-Z0-9]){8,20}$/;
            if (pattern.test(element.value)) {
                element.setAttribute('class', 'form-control is-valid');
            } else {
                element.setAttribute('class', 'form-control is-invalid');
            }
        }
    })
});