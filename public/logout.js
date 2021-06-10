function logoutConfirm() {
    const confirmation = alert('Etes vous sûr(e) de vous déconnecter ?');
    if (confirmation === true) {
        const profileId = document.getElementById('profile').value;
        const route = '/logout';
        document.location.href = route;
    }
}
