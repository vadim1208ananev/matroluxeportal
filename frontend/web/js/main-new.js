// (function () {
//     document.addEventListener('DOMContentLoaded', function () {

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function serialize(obj, prefix) {
    var str = [],
        p;
    for (p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p,
                v = obj[p];
            str.push((v !== null && typeof v === "object") ?
                serialize(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }
    return str.join("&");
}

function ajax(method, url, data, func) {
    let self = this;
    let request = new XMLHttpRequest();
    request.open(method, url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.setRequestHeader("x-csrf-token", csrfToken);
    request.responseText = 'json';
    request.send(data);
    request.onload = function (e) {
        func.call(self, JSON.parse(request.response));
    };
}

function sendForm(method, url, data) {
    const form = document.createElement('form');
    form.method = method;
    form.action = url;

    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = data[key];
            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
}

function getRadioValue(selector) {
    let value = '';
    document.querySelectorAll(selector).forEach(function (item) {
        if (item.checked == true)
            value = item.value;
    });
    return value;
}

function ael(el, type, func) {
    if (el == undefined) return;
    if (NodeList.prototype.isPrototypeOf(el) == false) {
        el.addEventListener(type, func);
    } else {
        el.forEach(function (item) {
            item.addEventListener(type, func);
        });
    }
};

// });
// })();