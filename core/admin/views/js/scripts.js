document.querySelector('.sitemap-button').onclick = (e) => {
    e.preventDefault();

    createSitemap();

}

let links_counter = 0;

function createSitemap() {

    links_counter++;

    Ajax({type: 'POST'})
        .then((res) => {
            console.log('успех - ' + res)
        })
        .catch((res) => {
            console.log('неудача - ' + res)
        });

}

