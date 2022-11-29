const csrfParam = "";
const csrfToken = "";
let buttonCart = document.querySelector('.product-list_cart');
let buttonSpec = document.querySelector('.product-list_spec');
let buttonsOrder = document.querySelectorAll('.product-list_order');
let buttonsSpec = document.querySelectorAll('.product-list_to-spec');
let menuFilterSave = document.querySelector('.menu_filter__save');
let menuFilterRestore = document.querySelector('.menu_filter__restore');
const mainBannerButtons = document.querySelectorAll('.main_banner__button');


if (document.querySelector('meta[name="csrf-param"]')) {
    const csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

let getParamCsrf = function () {
    "use strict";
    return "&" + csrfParam + "=" + csrfToken;
}

let serializeArray = function (form) {
    "use strict";
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

let isEmpty = function (obj) {
    "use strict";
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
    "use strict";
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
    "use strict";
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
    "use strict";
    // let productId = elem.dataset.productId;
    // let productList = elem.closest('.product-list');
    // let select = productList.querySelector('select');
    // let sizeId = select.options[select.selectedIndex].dataset.sizeId;
    // let amount = parseInt(productList.querySelector('input').value);
    const productId = productData.productId;
    const sizeId = productData.sizeId;
    const amount = productData.amount;
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

let redirectToSpec = function () {
    let storage = localStorage.getItem('spec');
    if (storage == null)
        storage = {};
    let url = '/specs';
    let form = '<form action="' + url + '" method="post" name="spec">' + '<input type="hidden" name="spec" value=' + storage + ' />' + '</form>';
    document.body.insertAdjacentHTML('beforeend', form);
    document.querySelector('form[name="spec"]').submit();
}

document.getElementById('searchform-q').addEventListener('keyup', function (e) {
    "use strict";
    let timeoutID = setTimeout(searchQuery.bind(undefined, e.target.value), 500);
    // timeoutID = setTimeout(searchQuery(e.target.value), 1500);
});

document.addEventListener('click', function (e) {
    let child = document.querySelector('.field.search_elems');
    if (child != null && !child.contains(e.target)) {
        child.innerHTML = '';
    }
});

document.querySelector('.search_submit').addEventListener('click', function () {
    'use strict';
    document.querySelector('.search_form').submit();
});

//redirect to cart
buttonCart.addEventListener('click', redirectToCart);

//redirect to spec
if (buttonSpec !== null) {
    buttonSpec.addEventListener('click', redirectToSpec);
}

Element.prototype.remove = function () {
    "use strict";
    this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function () {
    "use strict";
    for (let i = this.length - 1; i >= 0; i--) {
        if (this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

const addIsLoading = function (func, args) {
    args[0].classList.add('is-loading');
    func.apply(func, args);
    setTimeout(function () {
        args[0].classList.remove('is-loading');
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
