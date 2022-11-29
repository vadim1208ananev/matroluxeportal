// document.addEventListener("DOMContentLoaded", function () {
//     'use strict';

// const csrfParam = "";
// const csrfToken = "";
let buttonCart = document.querySelector('.product-list_cart');
let buttonSpec = document.querySelector('.product-list_spec');
let buttonsOrder = document.querySelectorAll('.product-list_order');
let buttonsSpec = document.querySelectorAll('.product-list_to-spec');
let menuFilterSave = document.querySelector('.menu_filter__save');
let menuFilterRestore = document.querySelector('.menu_filter__restore');
const mainBannerButtons = document.querySelectorAll('.main_banner__button');

// if (document.querySelector('meta[name="csrf-param"]')) {
//     const csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// }

// let getParamCsrf = function () {
//     return "&" + csrfParam + "=" + csrfToken;
// }

let serializeArray = function (form) {
    let arr = [];
    Array.prototype.slice.call(form.elements).forEach(function (field) {
        if (!field.name || field.disabled || ['file', 'reset', 'submit', 'button'].indexOf(field.type) > -1) return;
        if (field.type === 'select-multiple') {
            Array.prototype.slice.call(field.options).forEach(function (option) {
                if (!option.selected) return;
                arr.push({
                    name: field.name,
                    value: option.value
                });
            });
            return;
        }
        if (['checkbox', 'radio'].indexOf(field.type) > -1 && !field.checked) return;
        arr.push({
            name: field.name,
            value: field.value
        });
    });
    return arr;
};

function isEmpty(obj) {
    for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            return false;
        }
    }
    return JSON.stringify(obj) === JSON.stringify({});
}

let getAll = function (selector) {
    let parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document;
    return Array.prototype.slice.call(parent.querySelectorAll(selector), 0);
}

let searchQuery = function (str) {
    // console.log('search: ' + str);
    if (str.length <= 2)
        return;

    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let searchBlock = document.querySelector('.search_block');
            let child = document.querySelector('.field.search_elems');
            if (child !== null) {
                child.innerHTML = '';
                // console.log(3);
                // console.log(child);
            } else {
                // console.log(1);
                child = document.createElement('div');
                child.classList.add('field', 'search_elems');
            }
            child.innerHTML = this.responseText;
            console.log(typeof child);
            searchBlock.appendChild(child);
            // console.log(2);
        }
    }
    request.open("GET", "/site/search?q=" + str, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.send();
}

function getProductData(elem) {
    let data = {};
    const productList = elem.closest('.product-list');
    let select = productList.querySelector('select');
    if (select) {
        data.productId = elem.dataset.productId;
        data.sizeId = select.options[select.selectedIndex].dataset.sizeId;
        data.amount = parseInt(productList.querySelector('input').value);
    } else {
        data.productId = productList.querySelector('input[name="productId"]').value;
        data.sizeId = productList.querySelector('input[name="sizeId"]').value;
        data.amount = parseInt(productList.querySelector('input[name="amount"]').value);
    }
    return data;
}

let addToCartSpec = function (elem, productData, storageName) {
    // let productId = elem.dataset.productId;
    // let productList = elem.closest('.product-list');
    // let select = productList.querySelector('select');
    // let sizeId = select.options[select.selectedIndex].dataset.sizeId;
    // let amount = parseInt(productList.querySelector('input').value);
    const productId = productData.productId;
    const sizeId = productData.sizeId;
    const amount = productData.amount;
    if (amount <= 0)
        return;
    let storage = localStorage.getItem(storageName);
    if (storage == null) {
        storage = {};
    } else {
        storage = JSON.parse(storage);
    }
    // if (storage[productId]) {
    //     if (storage[productId][sizeId]) {
    //         storage[productId][sizeId] += amount;
    //     } else {
    //         storage[productId][sizeId] = amount;
    //     }
    // } else {
    //     storage[productId] = {};
    //     storage[productId][sizeId] = amount;
    // }
    if (!storage[productId]) {
        storage[productId] = {};
    }
    storage[productId][sizeId] = amount;
    localStorage.setItem(storageName, JSON.stringify(storage));

    if (!elem.classList.contains('no-run')) {
        elem.classList.remove('is-primary');
        elem.classList.add('is-warning');
        elem.textContent = 'В корзине';
    }

}

let redirectToCart = function () {
    let storage = localStorage.getItem('cart');
    if (storage == null)
        storage = {};
    let url = '/cart';
    let form = '<form action="' + url + '" method="post" name="cart">' + '<input type="hidden" name="cart" value=' + storage + ' />' + '</form>';
    document.body.insertAdjacentHTML('beforeend', form);
    document.querySelector('form[name="cart"]').submit();
}


function onLoadCart() {
    if (!window.location.href.includes('/cart')) {
        return;
    }
    const cart = localStorage.getItem('cart');
    if (cart == null)
        return;

    const request = new XMLHttpRequest();
    const url = "/cart";
    const params = "cart=" + cart;
    request.responseType = "text";
    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // request.addEventListener("readystatechange", () => {
    //     if (request.readyState === 4 && request.status === 200) {
    //         // let obj = request.response;
    //         // console.log(obj);
    //     }
    // });
    request.send(params);

    request.onload = function () {
        document.querySelector('h1').insertAdjacentHTML('afterend', request.response);
    };
}

function onLoadSpec() {
    if (!window.location.href.includes('/specs')) {
        return;
    }
    const spec = localStorage.getItem('spec');
    if (spec == null)
        return;

    const request = new XMLHttpRequest();
    const url = "/specs";
    const params = "spec=" + spec;
    request.responseType = "text";
    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // request.addEventListener("readystatechange", () => {
    //     if (request.readyState === 4 && request.status === 200) {
    //         // let obj = request.response;
    //         // console.log(obj);
    //     }
    // });
    request.send(params);

    request.onload = function () {
        document.querySelector('h2.current').insertAdjacentHTML('afterend', request.response);
    };
}

let redirectToSpec = function () {
    let storage = localStorage.getItem('spec');
    if (storage == null)
        storage = {};
    let url = '/specs';
    let form = '<form action="' + url + '" method="post" name="spec">' + '<input type="hidden" name="spec" value=' + storage + ' />' + '</form>';
    document.body.insertAdjacentHTML('beforeend', form);
    document.querySelector('form[name="spec"]').submit();
}

document.getElementById('searchform-q')?.addEventListener('keyup', function (e) {
    let timeoutID = setTimeout(searchQuery.bind(undefined, e.target.value), 500);
    // timeoutID = setTimeout(searchQuery(e.target.value), 1500);
});

document.addEventListener('click', function (e) {
    let child = document.querySelector('.field.search_elems');
    if (child != null && !child.contains(e.target)) {
        child.innerHTML = '';
    }
});

document.querySelector('.search_submit')?.addEventListener('click', function () {
    document.querySelector('.search_form').submit();
});

// //redirect to cart
//     buttonCart.addEventListener('click', redirectToCart);

// //redirect to spec
// if (buttonSpec !== null) {
//     buttonSpec.addEventListener('click', redirectToSpec);
// }

Element.prototype.remove = function () {
    this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function () {
    for (let i = this.length - 1; i >= 0; i--) {
        if (this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

const addIsLoading = function (func, args) {
    elem = args[0];
    elem.classList.add('is-loading');
    func.apply(func, args);
    setTimeout(function () {
        elem.classList.remove('is-loading');
    }, 250);
}

//add to local storage (cart)
Array.from(buttonsOrder).forEach(elem => {
    elem.addEventListener('click', () => {
        addIsLoading(addToCartSpec, [elem, getProductData(elem), 'cart']);
    });
});

//add to local storage (spec)
Array.from(buttonsSpec).forEach(elem => {
    elem.addEventListener('click', () => {
        addIsLoading(addToCartSpec, [elem, getProductData(elem), 'spec']);
    });
});

if (menuFilterSave !== null) {
    menuFilterSave.addEventListener('click', function () {
        localStorage.setItem('menu-filter-href', window.location.href);
    });
}

if (menuFilterRestore !== null) {
    menuFilterRestore.addEventListener('click', function () {
        const storage = localStorage.getItem('menu-filter-href');
        if (storage !== null) {
            window.location.replace(storage);
        }
    });
}

const menuFilterOnload = function () {
    if (menuFilterSave === null)
        return;
    const storage = localStorage.getItem('menu-filter-href');
    if (storage !== null) {
        //записываем href в restore
        const restore = document.querySelector(".menu_filter__restore");
        restore.href = storage;
    }
}

window.onload = function () {
    menuFilterOnload();
}

const handleBannerButtonMouseover = function () {
    this.querySelector('.active').style.display = 'none';
    this.querySelector('.inactive').style.display = 'inline-block';
}

const handleBannerButtonMouseout = function () {
    this.querySelector('.active').style.display = 'inline-block';
    this.querySelector('.inactive').style.display = 'none';
}

mainBannerButtons.forEach(function (elem) {
    elem.addEventListener('mouseenter', handleBannerButtonMouseover);
});

mainBannerButtons.forEach(function (elem) {
    elem.addEventListener('mouseleave', handleBannerButtonMouseout);
});

// Variables
let $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);


// Methods
let handleNavbarBurgers = function (el) {
    const target = el.dataset.target;
    const $target = document.getElementById(target);
    el.classList.toggle('is-active');
    $target.classList.toggle('is-active');
}

// Inits & Event Listeners
//handle navbar burgers
$navbarBurgers.forEach(elem => {
    elem.addEventListener('click', () => {
        handleNavbarBurgers(elem);
    });
});

const categories = getAll('.menu_category');
if (categories.length > 0) {
    categories.forEach(function (el) {
        const toggle_el = el.querySelector('.menu_label__toggle');
        toggle_el.addEventListener('click', function () {
            // closeCategories(el);
            el.classList.toggle('is-active');
        });
    });
}

document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('delete')) {
        let parent = e.target.parentNode;
        if (parent.contains(e.target) && parent.classList.contains('notification')) {
            parent.remove();
        }
    }
});

function handleCartHighligthing() {

    const cart = JSON.parse(localStorage.getItem('cart'));
    const products = document.querySelectorAll('.product-list');
    for (let i = 0; i < products.length; i++) {
        const product = products[i];
        if (product.dataset.productId in cart) {
            let order = product.querySelector('.product-list_order');
            if (order) {
                order.classList.remove('is-primary');
                order.classList.add('is-warning');
                order.textContent = 'В корзине';
            }
        }
    }
}

onLoadCart();
onLoadSpec();

window.onscroll = function () {
    fixHeader();
};

let header = document.querySelector('.hero__header');

function fixHeader() {
    if (window.pageYOffset > header.offsetTop) {
        header.classList.add("hero__sticky");
    } else {
        header.classList.remove("hero__sticky");
    }
}

// });
