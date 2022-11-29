document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const widthElem = document.querySelector('input[name="width"]');
    const lengthElem = document.querySelector('input[name="length"]');
    let buttonOrderNostandart = document.querySelector('.product-list_order_nostandart');

    const handleSizeId = function () {
        document.querySelector('input[name="sizeId"]').value = widthElem.value + 'x' + lengthElem.value;
    }

    const handleFocus = function (e, elem) {

        const name = typeof e == 'object' ? e.currentTarget.name : e;
        elem = elem == null ? this : elem;

        elem.nextSibling.textContent = '';
        if (elem.value)
            return true;
        elem.nextSibling.textContent = 'Необходимо заполнить «' + name + ' (см)»';
        return false;
    }

    const orderNostandart = function () {
        if (!(handleFocus('Ширина', widthElem) & handleFocus('Длина', lengthElem)))
            return;
        addIsLoading(addToCartSpec, [this, getProductData(this), 'cart']);

    }

    if (widthElem) {
        widthElem.addEventListener('input', handleSizeId);
        widthElem.addEventListener('focusout', handleFocus.bind(widthElem));
        widthElem.name = 'Ширина';
    }
    if (lengthElem) {
        lengthElem.addEventListener('input', handleSizeId)
        lengthElem.addEventListener('focusout', handleFocus.bind(lengthElem));
        lengthElem.name = 'Длина';
    }

    if (buttonOrderNostandart) buttonOrderNostandart.addEventListener('click', orderNostandart);

    handleCartHighligthing();

    document.querySelector('.modal-close').addEventListener('click', function (e) {
        this.closest('.modal').classList.remove('is-active');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key == 'Escape') {
            document.querySelector('.modal').classList.remove('is-active');
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.dataset.showImage) {
            if (e.target.dataset.showImage == 'true') {
                let modal = document.querySelector('.modal');
                modal.querySelector('.modal-content').innerHTML = `<img src="${e.target.src}">`;
                modal.classList.add('is-active');
            }
        }

    });

});