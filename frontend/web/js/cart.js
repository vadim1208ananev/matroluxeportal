document.addEventListener('DOMContentLoaded', () => {
    // 'use strict';

    let cartCreate = document.querySelector('.product-cart_order');
    const inputs = document.querySelectorAll('input[name="amount"]');
    // let buttonsDelete = document.querySelectorAll('.product-cart_delete');

    let handleOrderRequest = function (e) {
        e.preventDefault();
        let arr = {};
        let i = 0;
        let columns = document.querySelectorAll('.columns.product-list');
        const comment = document.querySelector('input[name="comment"]').value;
        let form = $(document).find('#cart-form');
        // const bonus = 0; //TODO убрать позже комментарий
        // const bonus = document.querySelector('input[name="bonus"]').value;
        Array.prototype.slice.call(columns).forEach(function (column) {
            let inputs = column.querySelectorAll('input');
            if (inputs.length == 0) return;
            arr[i] = {};
            Array.prototype.slice.call(inputs).forEach(function (input) {
                arr[i][input.name] = input.value;
            });
            i++;
        });
        // let form = document.querySelector('form');
        // form.submit();
        // let params = "cart=" + JSON.stringify(arr) + "&" + "comment=" + comment + "&" + "bonus=" + bonus; //TODO убрать позже комментарий
        // let data = "cart=" + JSON.stringify(arr) + "&" + "comment=" + comment;
        let data = '_csrf-frontend=' + yii.getCsrfToken() +
            '&cart=' + JSON.stringify(arr) +
            '&form=' + form.serialize() +
            '&cityRef=' + document.querySelector('input.search__city').dataset.ref +
            '&warehouseRef=' + document.querySelector('input.search__warehouse').dataset.ref +
            '&streetRef=' + document.querySelector('input.search__street').dataset.ref +
            '&comment=' + encodeURIComponent(comment) +
            '&deliveryServiceId=' + encodeURIComponent(getRadioValue('input[type=radio][name="CartForm[deliveryService]"]'));
        ajax.call(this, 'POST', '/cart/create', data, function (response) {
            // this.querySelector('.control').classList.remove('is-loading');
            // deleteLetterSearch();
            // if (response.success === true && Object.keys(response.data).length) {
            if (response.success === false && !isEmpty(response)) {
                for (let prop in response.data) {
                    const input = form.find('input[name="CartForm[' + prop + ']"]');
                    const help = input.find('~ p');
                    help.text(response.data[prop]);
                }
                return;
            }
            if (response.success)
                localStorage.removeItem('cart');
            let buttonOrder = document.querySelector('.product-cart_order');
            buttonOrder.insertAdjacentHTML('beforebegin', response.html);
            buttonOrder.style.display = 'none';
            document.querySelector('.cart-form').reset();
            // }
        });
    }

    let deleteFromCart = function (elem) {
        let productCart = elem.closest('.product-list');
        let productId = productCart.querySelector('input[name="productId"]').value;
        let sizeId = productCart.querySelector('input[name="sizeId"]').value;
        let storage = localStorage.getItem('cart');
        if (storage == null) {
            return;
        } else {
            storage = JSON.parse(storage);
        }
        if (storage[productId]) {
            if (storage[productId][sizeId]) {
                delete storage[productId][sizeId];
                if (isEmpty(storage[productId])) {
                    delete storage[productId];
                }
            }
        }
        localStorage.setItem('cart', JSON.stringify(storage));

        // let url = '/cart';
        // let form = '<form action="' + url + '" method="post" name="cart">' + '<input type="hidden" name="cart" value=' + JSON.stringify(storage) + '  />' + '</form>';
        // document.body.insertAdjacentHTML('beforeend', form);
        // document.querySelector('form[name="cart"]').submit();
        window.location.href = "/cart";
    }


    if (cartCreate)
        cartCreate.addEventListener('click', handleOrderRequest);

    // Array.from(buttonsDelete).forEach(elem => {
    //     elem.addEventListener('click', () => {
    //         deleteFromCart(elem);
    //     });
    // });

    $(document).on('click', '.product-cart_delete', function () {
        deleteFromCart(this);
    });

    // inputs.forEach(function (elem) {
    //     elem.addEventListener('input', handleChangeAmount);
    // });

    $(document).on('input', 'input[name="amount"]', function () {
        handleChangeAmount(this);
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete')) {
            let parent = e.target.parentNode;
            if (parent.contains(e.target) && parent.classList.contains('notification')) {
                parent.remove();
            }
        }
    });

    function handleChangeAmount(elem) {
        // const amount = elem.value;
        // if (amount <= 0) {
        //     elem.value = elem.defaultValue;
        //     return;
        // }
        addIsLoading(addToCartSpec, [elem, getProductData(elem), 'cart']);
    }


    //................................................toggle type service...........................................
    let form = document.querySelector('#cart-form');
    let inputServiceType = form.querySelectorAll('input[name="CartForm[serviceType]"]:not([type="hidden"])');
    for (let i = 0; i < inputServiceType.length; i++) {
        inputServiceType[i].addEventListener('change', function (e) {
            // let typeService = e.target.value;
            let items = document.querySelectorAll('.cart-form .cart-form__item');
            for (let j = 0; j < items.length; j++) {
                items[j].classList.toggle('active');
            }
        });
    }

    //................................................focus, blur inputs by hand....................................
    const inputsByHand = document.querySelectorAll('.cart-form .cart-form__by-hand');

    for (let i = 0; i < inputsByHand.length; i++) {
        inputsByHand[i].addEventListener('focusout', function (e) {
            let typeService = document.querySelectorAll('input[name="CartForm[serviceType]"]:not([type="hidden"]):checked')[0].defaultValue;
            if (e.currentTarget.querySelector('input').value !== ''
                && ((typeService == 'DoorsWarehouse' && e.currentTarget.classList.contains('cart-form__warehouse')) ||
                    (typeService == 'DoorsDoors' && e.currentTarget.classList.contains('cart-form__doors')))) {
                e.currentTarget.querySelector('p').textContent = '';
            }
        });
    }

    //.......................................................city-search............................................
    let handlers = document.querySelectorAll('.column[data-search-handler]');
    let searchContainer = document.querySelector('.search__container');

    function openLetterSearch() {
        let html = '';
        let div = document.createElement('div');
        let input = this.querySelector('input');
        // let coords = getCoords(self);

        let attr = this.getAttribute('data-search-handler');
        if (attr == 'city') {
            if (input.value.length <= 2)
                return;
            searchContainer.querySelector('.search__warehouse').value = '';
            searchContainer.querySelector('.search__street').value = '';

            let data = '_csrf-frontend=' + yii.getCsrfToken() +
                '&city=' + encodeURIComponent(input.value) +
                '&deliveryServiceId=' + encodeURIComponent(getRadioValue('input[type=radio][name="CartForm[deliveryService]"]'));
            // searchContainer.querySelector('.column[data-search-handler="city"] .control').classList.add('is-loading');
            this.querySelector('.control').classList.add('is-loading');
            div.className = 'letter-search letter-search__city';
            ajax.call(this, 'POST', '/cart/get-cities', data, function (response) {
                this.querySelector('.control').classList.remove('is-loading');
                deleteLetterSearch();
                if (response.success === true && Object.keys(response.data).length) {
                    for (let area in response.data) {
                        html += `<div class="letter-search__area">${area} обл.</div>`;
                        let item = response.data[area];
                        for (let j = 0; j <= item.length - 1; j++) {
                            html += `<div class="letter-search__city-item" data-ref="${item[j].ref}">${item[j].description} ${item[j].type}</div>`;
                        }
                    }
                } else {
                    html += '<div class="letter-search__city-item letter-search__not-found">По запросу ничего не найдено</div>';
                    div.style.overflowY = 'unset';
                }
                div.innerHTML = html;
                // div.style.width = self.offsetWidth - 24 + 'px';
                div.style.display = 'block';
                this.append(div);
            });
        } else if (attr == 'warehouse') {
            let data = '_csrf-frontend=' + yii.getCsrfToken() +
                '&cityRef=' + encodeURIComponent(searchContainer.querySelector('input.search__city').dataset.ref) +
                '&deliveryServiceId=' + encodeURIComponent(getRadioValue('input[type=radio][name="CartForm[deliveryService]"]'));
            this.querySelector('.control').classList.add('is-loading');
            deleteLetterSearch();
            div.className = 'letter-search letter-search__warehouse';
            ajax.call(this, 'POST', '/cart/get-warehouses', data, function (response) {
                this.querySelector('.control').classList.remove('is-loading');
                if (response.success === true) {
                    if (response.data.length) {
                        for (let i = 0; i <= response.data.length - 1; i++) {
                            html += `<div class="letter-search__warehouse-item" data-ref="${response.data[i].ref}">${response.data[i].description}</div>`;
                        }
                    } else {
                        html += '<div class="letter-search__warehouse-item letter-search__not-found">Не найдены грузовые отделения</div>';
                        div.style.overflowY = 'unset';
                    }
                } else {
                    html += '<div class="letter-search__warehouse-item letter-search__not-found">По запросу ничего не найдено</div>';
                    div.style.overflowY = 'unset';
                }
                div.innerHTML = html;
                // div.style.width = self.offsetWidth - 24 + 'px';
                div.style.display = 'block';
                this.append(div);
            });
        } else if (attr == 'street') {
            if (input.value.length <= 2)
                return;
            // searchContainer.querySelector('.search__warehouse').value = '';
            // searchContainer.querySelector('.search__street').value = '';
            let data = '_csrf-frontend=' + yii.getCsrfToken() +
                '&cityRef=' + encodeURIComponent(searchContainer.querySelector('input.search__city').dataset.ref) +
                '&street=' + encodeURIComponent(input.value) +
                '&deliveryServiceId=' + encodeURIComponent(getRadioValue('input[type=radio][name="CartForm[deliveryService]"]'));
            this.querySelector('.control').classList.add('is-loading');
            div.className = 'letter-search letter-search__street';
            ajax.call(this, 'POST', '/cart/get-streets', data, function (response) {
                this.querySelector('.control').classList.remove('is-loading');
                deleteLetterSearch();
                if (response.success === true) {
                    if (response.data.length) {
                        for (let i = 0; i <= response.data.length - 1; i++) {
                            html += `<div class="letter-search__street-item" data-ref="${response.data[i].ref}">${response.data[i].description}</div>`;
                        }
                    }
                } else {
                    html += '<div class="letter-search__street-item letter-search__not-found">По запросу ничего не найдено</div>';
                    div.style.overflowY = 'unset';
                }
                div.innerHTML = html;
                // div.style.width = self.offsetWidth - 24 + 'px';
                div.style.display = 'block';
                this.append(div);
            });
        }
    }

    function hiddenSearch(e) {
        let letterSearch = document.querySelector('.letter-search');
        if (letterSearch == null) return;
        for (let i = 0; i < handlers.length; i++) {
            if (!handlers[i].contains(e.target) && (!letterSearch.contains(e.target))) {
                letterSearch.style.display = 'none';
                //     for (let i = 0; i < producersItem.length; i++) {
                //         producersItem[i].classList.remove('active');
                //     }
            }
        }
    }

    for (let i = 0; i < handlers.length; i++) {
        handlers[i].addEventListener('input', function () {
            setTimeout(openLetterSearch.bind(this), 1000);
        });
    }

    document.addEventListener("mouseup", hiddenSearch.bind(this));

    document.addEventListener('click', function (e) {

        let input;
        if (e.target && !e.target.classList.contains('letter-search__not-found')) {
            let item = e.target;
            if (e.target.classList.contains('letter-search__city-item')) {
                input = searchContainer.querySelector('input.search__city');
                input.value = e.target.textContent;
                input.dataset.ref = e.target.dataset.ref;
                deleteLetterSearch();
                openLetterSearch.call(document.querySelector('.search__container .column[data-search-handler="warehouse"]'));
                document.querySelector('.city-ref').value = e.target.dataset.ref;
            } else if (e.target.classList.contains('letter-search__warehouse-item')) {
                input = searchContainer.querySelector('input.search__warehouse');
                input.value = e.target.textContent;
                input.dataset.ref = e.target.dataset.ref;
                document.querySelector('.warehouse-ref').value = e.target.dataset.ref;
                deleteLetterSearch();
            } else if (e.target.classList.contains('letter-search__street-item')) {
                input = searchContainer.querySelector('input.search__street');
                input.value = e.target.textContent;
                input.dataset.ref = e.target.dataset.ref;
                document.querySelector('.street-ref').value = e.target.dataset.ref;
                deleteLetterSearch();
            }
        }
    });

    searchContainer.querySelector('.search__warehouse').addEventListener('focus', function () {
        openLetterSearch.call(document.querySelector('.search__container .column[data-search-handler="warehouse"]'));
    });

    //.......................................................common.................................................
    function getCoords(elem) {
        let box = elem.getBoundingClientRect();

        return {
            top: box.top + pageYOffset,
            left: box.left + pageXOffset
        };
    }

    function deleteLetterSearch() {
        let letterSearch = searchContainer.querySelector('.letter-search');
        if (letterSearch !== null) {
            letterSearch.remove();
        }
    }

    document.querySelector('#cartform-isdelivery').addEventListener('change', function () {
        document.querySelector('.cart__delivery').classList.toggle('active');
    });


    document.querySelectorAll('input[type=radio][name="CartForm[deliveryService]"]').forEach(function (elem) {
        elem.addEventListener('click', function () {
            document.querySelector('.city-ref').value = '';
            document.querySelector('.warehouse-ref').value = '';
            document.querySelector('.street-ref').value = '';
            handlers.forEach(function (handler) {
                handler.querySelector('input').value = '';
                handler.querySelector('input').dataset.ref = '';
            });
        });
    });

});