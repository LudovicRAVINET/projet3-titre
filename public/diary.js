function update(element, id) {
    const currentValue = element.innerText;
    const data = new FormData();
    data.set('prop', element.id);
    data.set('value', currentValue);
    /* eslint-disable */
    axios({
        method: 'post',
        dataType: 'json',
        url: `/wedding/${id}/bannerDiary`,
        data,
    })
        .catch((err) => {
            throw err;
        });
    /* eslint-enable */
}

function pictureUpload(eventId) {
    const form = document.querySelector('#uploadForm');
    form.addEventListener('submit', (evt) => {
        evt.preventDefault();
        const imageFile = document.querySelector('#file');
        const formData = new FormData();
        formData.append('image', imageFile.files[0]);
        /* eslint-disable */
        axios({
            method: 'post',
            url: `/wedding/${eventId}/bannerDiary`,
            data: formData,
            headers: { 'Content-Type': 'multipart/form-data' },
        })
            .then((res) => res.data.image)
            .then((image) => {
                const weddingImageHtml = `
                <img src="/uploads/files/${image}" class="d-block w-100" alt="Diary wedding banner">
            `;
                document.querySelector('#wedding-image').innerHTML = weddingImageHtml;
            })
            .catch((err) => {
                throw err;
            });
        /* eslint-enable */
    });
}
