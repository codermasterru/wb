; (function () {
    // создаем глобальный объект
    window.myLib = {};

    // Инициализируем переменную  body
    window.myLib.body = document.querySelector('body');

    // Находим нужный аттрибут
    window.myLib.closestAttr = function (item, attr) {
        let node = item;

        while (node) {
            let attrValue = node.getAttribute(attr);
            if (attrValue) {
                return attrValue;
            }
            node = node.parentElement;
        }
        return null;
    };

    window.myLib.closestItemByClass = function (item, className) {
        var node = item;

        while (node) {
            if (node.classList.contains(className)) {
                return node;
            }

            node = node.parentElement;
        }

        return null;
    };

    window.myLib.toggleScroll = function () {
        myLib.body.classList.toggle('no-scroll');
    };
})();