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
function avis() {
    popup('avisBtnClose');
    popup('avisBtn');
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

function menuDisplay() {
    const nav = document.getElementById('nav');
    const loginBtn = document.getElementById('loginBtnNav');
    const registerBtn = document.getElementById('registerBtnNav');
    const dropdownDiv = document.getElementById('dropdownDiv');
    nav.classList.toggle('nav_open');
    if (dropdownDiv != null) {
        dropdownDiv.classList.toggle('visually-hidden');
    }
    if (loginBtn != null && registerBtn != null) {
        loginBtn.classList.toggle('visually-hidden');
        registerBtn.classList.toggle('visually-hidden');
    }
}

function activitiesDisplay() {
    const weddingLink = document.getElementById('weddingLink');
    const birthdateLink = document.getElementById('birthdateLink');
    const mourningLink = document.getElementById('mourningLink');
    const chevron = document.getElementById('chevron');
    const activityLink = document.getElementById('activityLink');
    weddingLink.classList.toggle('visually-hidden');
    birthdateLink.classList.toggle('visually-hidden');
    mourningLink.classList.toggle('visually-hidden');
    chevron.classList.toggle('visually-hidden');
    activityLink.classList.toggle('text-uppercase');
}

function createEventBtn(element, evtType) {
    const createBtns = document.getElementsByClassName('btnEvent');
    const eventType = document.getElementById('eventType');
    const inputEvent = `<input type="text" name="event_type" id="event_type" value="${evtType}">`;
    Array.from(createBtns).forEach((btn) => {
        if (btn.classList.contains('pushedBtn')) {
            btn.classList.remove('pushedBtn');
        }
    });
    element.classList.toggle('pushedBtn');
    eventType.innerHTML = inputEvent;
}
