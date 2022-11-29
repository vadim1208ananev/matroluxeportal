document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const copies = document.querySelectorAll('.order-list_copy');

    function createCopy(e) {
        const request = new XMLHttpRequest();
        const url = "/orders/copy";
        const params = "order-id=" + this.dataset.orderId;
        request.responseType = "text";
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(params);

        request.onload = function () {
            let storage = localStorage.getItem('cart');
            if (storage == null) {
                storage = {};
            } else {
                storage = JSON.parse(storage);
            }
            const response = JSON.parse(request.response);
            if (response.success) {
                const data = JSON.parse(response.data);
                for (let productId in data) {
                    for (let sizeId in data[productId]) {
                        if (!storage[productId]) {
                            storage[productId] = {};
                        }
                        let current = storage[productId][sizeId] == null ? 0 : storage[productId][sizeId];
                        storage[productId][sizeId] = current + data[productId][sizeId];
                    }
                }
                localStorage.setItem('cart', JSON.stringify(storage));
                let orderList = document.querySelector('.order-container');
                orderList.insertAdjacentHTML('beforeend', response.html);
            }
        };
    }

    for (let i = 0; i < copies.length; i++) {
        copies[i].addEventListener("click", createCopy, false);
    }

});