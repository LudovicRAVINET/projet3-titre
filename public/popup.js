function popup() {
    const popupDiv = document.querySelector('#popupForm');
    /* eslint-disable */
    axios.get('/login')
    .then((res) => res.data)
    .then((data) => {
        popupDiv.innerHTML = data;
        document.getElementById("loginBtn").click();
    })
    .catch((err) => {
        throw err;
    });
    /* eslint-enable */
}

function passwordEye() {
    document.getElementById('eye').classList.toggle('fa-eye-slash');
    const pwd = document.getElementById('inputPassword');
    if (pwd.getAttribute('type') === 'password') {
        pwd.setAttribute('type', 'text');
    } else {
        pwd.setAttribute('type', 'password');
    }
}
