function popup(btn) {
    document.getElementById(btn).click();
}

function registerLogin() {
    popup('registerBtnClose');
    popup('loginBtn');
}

function loginRegister() {
    popup('loginBtnClose');
    popup('registerBtn');
}

function resetPassword() {
    popup('loginBtnClose');
    popup('passwordBtn');
}

const newUser = document.getElementById('newUser').value;
if (newUser === '1') {
    popup('confirmBtn');
}

const error = document.getElementById('connectError').value;
if (error !== '') {
    popup('loginBtn');
}

function passwordEye(id, inputPwd) {
    document.getElementById(id).classList.toggle('fa-eye-slash');
    const pwd = document.getElementById(inputPwd);
    if (pwd.getAttribute('type') === 'password') {
        pwd.setAttribute('type', 'text');
    } else {
        pwd.setAttribute('type', 'password');
    }
}
