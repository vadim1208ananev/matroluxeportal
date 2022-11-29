document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    // let buttonsDelete = document.querySelectorAll('.product-cart_delete');
    let specCreate = document.querySelector('.product-cart_spec');
    let buttonsSpecDelete = document.querySelectorAll('.spec_delete');
    let buttonsOrderCreate = document.querySelectorAll('.spec_order');
    const buttonsSpecCurSave = document.querySelectorAll('.product-list_spec-cur__save');
    const buttonsSpecCurDelete = document.querySelectorAll('.product-list_spec-cur__delete');
    const inputs = document.querySelectorAll('input[name="amount"]');

    let handleSpecRequest = function () {
        let arr = {};
        let i = 0;
        let columns = document.querySelectorAll('.columns.product-list');
        const comment = document.querySelector('input[name="comment"]').value;
        Array.prototype.slice.call(columns).forEach(function (column) {
            let inputs = column.querySelectorAll('input');
            if (inputs.length == 0) return; //TODO не знаю, откуда берется пустой inputs
            arr[i] = {};
            Array.prototype.slice.call(inputs).forEach(function (input) {
                arr[i][input.name] = input.value;
            });
            i++;
        });
        // let form = document.querySelector('form');
        // form.submit();
        let request = new XMLHttpRequest();
        let url = "/spec/create";
        let params = "spec=" + JSON.stringify(arr) + "&" + "comment=" + comment;
        request.responseType = "text";
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
            if (request.readyState === 4 && request.status === 200) {
                // let obj = request.response;
                // console.log(obj);
            }
        });
        request.send(params);

        request.onload = function () {
            let buttonSpec = document.querySelector('.product-cart_spec');
            buttonSpec.insertAdjacentHTML('beforebegin', request.response);
        };
    }

    let handleOrderRequest = function (elem) {
        const spec = elem.closest('.spec-list');
        let specId = '';
        if (spec) {
            specId = spec.dataset.specId;
        } else {
            specId = document.querySelector('input[name="specId"]').value;
        }

        let request = new XMLHttpRequest();
        const url = "/specs/order/" + specId;
        // request.responseType = "text";
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
            if (request.readyState === 4 && request.status === 200) {
            }
        });
        request.send(null);

        request.onload = function () {
            let buttonSpec = document.querySelector('.spec-list_container');
            buttonSpec.insertAdjacentHTML('beforeend', request.response);
        };
    }

    let deleteFromSpec = function (elem) {
        let productSpec = elem.closest('.product-list');
        let productId = productSpec.querySelector('input[name="productId"]').value;
        let sizeId = productSpec.querySelector('input[name="sizeId"]').value;
        let storage = localStorage.getItem('spec');
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
        localStorage.setItem('spec', JSON.stringify(storage));

        // let url = '/specs';
        // let form = '<form action="' + url + '" method="post" name="spec">' + '<input type="hidden" name="spec" value=' + JSON.stringify(storage) + '  />' + '</form>';
        // document.body.insertAdjacentHTML('beforeend', form);
        // document.querySelector('form[name="spec"]').submit();
        window.location.href = "/specs";
    }

    let deleteFromSpecDb = function (elem) {
        const spec = elem.closest('.spec-list');
        const specId = spec.dataset.specId;
        let storage = localStorage.getItem('spec');
        if (storage == null) {
            storage = {};
        } else {
            storage = JSON.parse(storage);
        }

        let request = new XMLHttpRequest();
        const url = "/specs/delete/" + specId;
        // const params = "specId=" + specId;
        // request.responseType = "text";
        request.open("DELETE", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
            if (request.readyState === 4 && request.status === 200) {
            }
        });
        // request.send(params);
        request.send();

        request.onload = function () {
            let url = '/specs';
            let form = '<form action="' + url + '" method="post" name="spec">' + '<input type="hidden" name="spec" value=' + JSON.stringify(storage) + '  />' + '</form>';
            document.body.insertAdjacentHTML('beforeend', form);
            document.querySelector('form[name="spec"]').submit();
        };
    }

    if (specCreate) {
        specCreate.addEventListener('click', handleSpecRequest);
    }

    // Array.from(buttonsDelete).forEach(elem => {
    //     elem.addEventListener('click', () => {
    //         deleteFromSpec(elem);
    //     });
    // });

    $(document).on('click', '.product-cart_delete', function () {
        deleteFromSpec(this);
    });

    Array.from(buttonsSpecDelete).forEach(elem => {
        elem.addEventListener('click', () => {
            deleteFromSpecDb(elem);
        });
    });

    Array.from(buttonsOrderCreate).forEach(elem => {
        elem.addEventListener('click', () => {
            handleOrderRequest(elem);
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete')) {
            let parent = e.target.parentNode;
            if (parent.contains(e.target) && parent.classList.contains('notification')) {
                parent.remove();
            }
        }
    });

    function changeSpecCurAmountAjax(elem, data) {
        const request = new XMLHttpRequest();
        const url = "/specs/update/" + data.specId;
        const params = "data=" + JSON.stringify(data);
        // request.responseType = "text";
        request.open("PUT", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        elem.classList.add('is-loading');
        request.send(params);
        request.onload = function () {
            elem.classList.remove('is-loading');
        };
    }

    function handleChangeAmount() {
        let productData = getProductData(this);
        productData.specId = document.querySelector('input[name="specId"]').value;
        const productList = this.closest('.product-list');
        const input = productList.querySelector('input[name="amount"]');
        if (productData.amount <= 0) {
            input.value = input.defaultValue;
            return;
        }
        changeSpecCurAmountAjax(this, productData);
    }

    function handleDeleteSpecCur() {
        let productData = getProductData(this);
        productData.specId = document.querySelector('input[name="specId"]').value;
        const request = new XMLHttpRequest();
        const url = "/specs/delete-current/" + productData.specId;
        const params = "data=" + JSON.stringify(productData);
        // request.responseType = "text";
        request.open("DELETE", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        this.classList.add('is-loading');
        request.send(params);
        request.onload = function () {
            window.location.replace(window.location.href);
        };
    }

    buttonsSpecCurSave.forEach(function (elem) {
        elem.addEventListener('click', handleChangeAmount);
    });

    buttonsSpecCurDelete.forEach(function (elem) {
        elem.addEventListener('click', handleDeleteSpecCur);
    });

    function handleChangeCurAmount(elem) {
        // const amount = this.value;
        // if (amount <= 0) {
        //     this.value = this.defaultValue;
        //     return;
        // }
        addIsLoading(addToCartSpec, [elem, getProductData(elem), 'spec']);
    }

    // inputs.forEach(function (elem) {
    //     elem.addEventListener('input', handleChangeCurAmount);
    // });

    $(document).on('input', 'input[name="amount"]', function () {
        handleChangeCurAmount(this);
    });

});