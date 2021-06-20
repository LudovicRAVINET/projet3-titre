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
