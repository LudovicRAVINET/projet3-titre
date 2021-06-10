function logoutConfirm() {
    if (window.confirm('Etes vous sûr(e) de vous déconnecter ?')) {
        const profileId = document.getElementById('profile').value;
        const route = '/logout';
        document.location.href = route;
    }
}
