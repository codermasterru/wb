document.querySelector('.sitemap-button').onclick = (e) => {
    e.preventDefault();

    createSitemap();

}

let links_counter = 0;

function createSitemap() {

    links_counter++;

    Ajax({data: {ajax: 'sitemap', links_counter: links_counter}})
        .then((res) => {
            console.log('успех - ' + res);
        })
        .catch((res) => {
            console.log('ошибка - ' + res);
            createSitemap();
        });
}

//  delete img

let files = document.querySelectorAll('input[type=file]');

let fileStore = [];

if (files.length) {

    files.forEach(item => {

        item.onchange = function () {

            let multiple = false;

            let parentContainer;

            let container;

            if (item.hasAttribute('name'))


                };

    })

}

