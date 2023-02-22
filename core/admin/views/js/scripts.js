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

            let area = item.closest('.img_wrapper');

            if (area) {

                dragAndDrop(area, item);

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

        function dragAndDrop(area, input) {

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName, index) => {

                area.addEventListener(eventName, e => {

                    e.preventDefault();

                    e.stopPropagation();

                    if (index < 2) {

                        area.style.background = 'lightblue';

                    } else {

                        area.style.background = '#fff';

                        if (index === 3) {

                            input.files = e.dataTransfer.files;

                            input.dispatchEvent(new Event('change'));

                        }
                    }

                })

            });

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

function blockParameters() {

    let wraps = document.querySelectorAll('.select_wrap');

    if (wraps.length) {

        let selectAllIndexes = [];

        wraps.forEach(item => {

            let next = item.nextElementSibling;

            if (next && next.classList.contains('option_wrap')) {

                item.addEventListener('click', e => {

                    if (!e.target.classList.contains('select_all')) {

                        next.slideToggle()

                    } else {

                        let index = [...document.querySelectorAll('.select_all')].indexOf(e.target);

                        if (typeof selectAllIndexes[index] === 'undefined') selectAllIndexes[index] = false;

                        selectAllIndexes[index] = !selectAllIndexes[index];

                        next.querySelectorAll('input[type=checkbox]').forEach(el => {

                            el.checked = selectAllIndexes[index];

                        })

                    }

                })

            }
        });

    }

}

showHideMenuSearch();

function showHideMenuSearch() {

    document.querySelector('#hideButton').addEventListener('click', () => {

        document.querySelector('.vg-carcass').classList.toggle('vg-hide');

    });

    let searchBtn = document.querySelector('#searchButton');

    let searchInput = searchBtn.querySelector('input[type]');

    searchBtn.addEventListener('click', () => {

        searchBtn.classList.add('vg-search-reverse');

        searchInput.focus()
    });

    searchInput.addEventListener('blur', () => {

        searchBtn.classList.remove('vg-search-reverse')

    });
}


let searchResultHover = (() => {

    let searchRes = document.querySelector('.search_res');

    let searchInput = document.querySelector('#searchButton input[type=text]');

    let defaultInputValue = null;


    function searchKeyDown(e) {

        if (!(document.querySelector('#searchButton').classList.contains('vg-search-reverse')) ||
            (e.key !== 'ArrowUp' && e.key !== 'ArrowDown')) return;

        let children = [...searchRes.children];


        if (children.length) {

            e.preventDefault();

            let activeItem = searchRes.querySelector('.search_act');

            let activeIndex = activeItem ? children.indexOf(activeItem) : -1;

            if (e.key === 'ArrowUp')
                activeIndex = activeIndex <= 0 ? children.length - 1 : --activeIndex;
            else
                activeIndex = activeIndex === children.length - 1 ? 0 : ++activeIndex;

            children.forEach(item => item.classList.remove('search_act'));

            children[activeIndex].classList.add('search_act');

            searchInput.value = children[activeIndex].innerText;
        }

    }

    function setDefaultValue() {

        searchInput.value = defaultInputValue;

    }


    searchRes.addEventListener('mouseleave', setDefaultValue);

    window.addEventListener('keydown', searchKeyDown);

    return () => {


        defaultInputValue = searchInput.value;

        if (searchRes.children.length) {

            let children = [...searchRes.children];

            children.forEach(item => {

                item.addEventListener('mouseover', () => {


                    children.forEach(el => el.classList.remove('search_act'));

                    item.classList.add('search_act');

                    searchInput.value = item.innerText;

                });


            });

        }

    }

})();

searchResultHover();

//   #118

document.addEventListener('DOMContentLoaded', () => {

    function hideMessages() {

        document.querySelectorAll('.success, .error').forEach(item => {item.remove() });

        document.removeEventListener('click', hideMessages)

    }

    document.addEventListener('click', hideMessages);
})





















