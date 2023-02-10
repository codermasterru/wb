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

createFile();

function createFile() {
    let files = document.querySelectorAll('input[type=file]');


// Массив с хранилищем файлов
    let fileStore = [];


// Если есть инпуты
    if (files.length) {

        // Перебираем  их
        files.forEach(item => {

            // При каком нибудь изменении
            item.onchange = function () {

                // Флаг множественной вставки
                let multiple = false;

                // Контейнер , в который мы будем добавлять изображения
                let parentContainer;

                //
                let container;


                // Является ли разыскиваемый инпут , инпутом  с множественным добавлением
                if (item.hasAttribute('multiple')) {

                    // флаг в true
                    multiple = true;

                    // Родительский контейнер
                    parentContainer = this.closest('.gallery_container');


                    // Если нет контейнера
                    if (!parentContainer) return false;

                    // Выбрать все пустые контейнеры
                    container = parentContainer.querySelectorAll('.empty_container');

                    if (container.length < this.files.length) {

                        for (let index = 0; index < this.files.length - container.length; index++) {

                            let el = document.createElement('div');

                            el.classList.add('vg-dotted-square', 'vg-center', 'empty_container');

                            parentContainer.append(el);

                        }

                        container = parentContainer.querySelectorAll('.empty_container');

                    }

                }

                // console.log(this.files);

                let fileName = item.name;

                let attributeName = item.name.replace(/[\[\]]/g, '');

                for (let i in this.files) {

                    if (this.files.hasOwnProperty(i)) {

                        if (multiple) {

                            if (typeof fileStore[fileName] === 'undefined') fileStore[fileName] = [];

                            let elId = fileStore[fileName].push(this.files[i]) - 1;

                            container[i].setAttribute(`data-deleteFileId-${attributeName}`, elId);

                            showImage(this.files[i], container[i]);

                            deleteNewFiles(elId, fileName, attributeName, container[i]);


                        } else {

                            container = this.closest('.img_container').querySelector('.img_show');

                            showImage(this.files[i], container);


                        }

                    }

                }
            }
        })

        function showImage(item, container) {


            // Объект FileReader позволяет веб-приложениям асинхронно читать содержимое файлов
            let reader = new FileReader();

            container.innerHTML = '';

            reader.readAsDataURL(item);

            reader.onload = e => {

                container.innerHTML = '<img class="img_item" src="" alt="">';

                container.querySelector('img').setAttribute('src', e.target.result);

                container.classList.remove('.empty_container');
            }

        }

        let form = document.querySelector('#main-form');

        if (form) {

            form.onsubmit = function (e) {


                if (!isEmpty(fileStore)) {

                    e.preventDefault();

                    let formData = new FormData(this);

                    //     console.log(formData.get('name'));

                    for (let i in fileStore) {

                        if (fileStore.hasOwnProperty(i)) {

                            formData.delete(i);

                            let rowName = i.replace(/[\[\]]/g, '');

                            fileStore[i].forEach((item, index) => {

                                formData.append(`${rowName}[${index}]`, item);

                            })

                        }

                    }

                    formData.append('ajax', 'editData');

                    Ajax({
                        url: this.getAttribute('action'),
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false
                    }).then(res => {

                        try {

                            res = JSON.parse(res);

                            if (!res.success) throw new Error();

                            location.reload();

                        } catch (e) {

                            alert('Произошла внутренняя ошибка');

                        }

                    })

                }

            }

        }

        function deleteNewFiles(elId, fileName, attributeName, container) {

            container.addEventListener('click', function () {

                this.remove();

                delete fileStore[fileName][elId];

                console.log(fileStore);

            })

        }
    }
}

changeMenuPosition();

function changeMenuPosition() {

    // Нашли форму
    let form = document.querySelector('#main-form');

    // Если форма есть
    if (form) {

        let selectParent = document.querySelector('select[name=parent_id]');


        let selectPosition = document.querySelector('select[name=menu_position]');

        if (selectParent && selectPosition) {

            let defaultParent = selectParent.value;

            let defaultPosition = +selectPosition.value

            selectParent.addEventListener('change', function () {

                let defaultChoose = false;

                if (this.value === defaultParent) defaultChoose = true;

                Ajax({
                    data: {
                        table: form.querySelector('input[name=table]').value,
                        'parent_id': this.value,
                        ajax: 'change_parent',
                        iteration: !form.querySelector('#tableId') ? 1 : +!defaultChoose

                    }
                }).then(res => {

                    res = +res;

                    if (!res) return errorAlert();

                    let newSelect = document.createElement('select');

                    newSelect.setAttribute('name', 'menu_position');

                    newSelect.classList.add('vg-input', 'vg-text', 'vg-full', 'vg-firm-color1');

                    for (let i = 1; i <= res; i++) {

                        let selected = defaultChoose && i === defaultPosition ? 'selected' : ''

                        newSelect.insertAdjacentHTML('beforeend', `<option ${selected} value="${i}">${i}</option>`);

                    }

                    selectPosition.before(newSelect);

                    selectPosition.remove();

                    selectPosition = newSelect;

                });

            });

        }


    }


}


blockParameters();
function blockParameters(){

}








