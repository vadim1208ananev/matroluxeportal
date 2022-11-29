let map;
let markers = [], ids = [], idsExcept = [];
let statuses = {}, types = {};
let checkboxes = document.querySelectorAll("input[type='checkbox']");

const path = 'http://maps.google.com/mapfiles/ms/icons/';
const colors = {
    'Активные дистрибуция': path + 'blue-dot.png',
    'Активные, под заказ': path + 'green-dot.png',
    'НЕ– Активные Дистрибуция (потенциал)': path + 'yellow-dot.png',
    'ТТ закрылась': path + 'red-dot.png',
    'Не заполнен (Статус)': path + 'purple-dot.png',
    'Не заполнен (Тип)': path + 'purple-dot.png',
};

for (let i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('click', handleCheckboxes);
}

function handleCheckboxes(e) {
    if (statuses[e.target.name] !== undefined) {
        statuses[e.target.name].checked = !statuses[e.target.name].checked;
    }
    for (let i = 0; i < checkboxes.length; i++) {
        let checkbox = checkboxes[i];
        if (statuses[checkbox.name] !== undefined) {
            let status = statuses[checkbox.name];
            if (!status.checked) {
                for (let j = 0; j < status.data.length; j++) {
                    if (!idsExcept.includes(status.data[j]))
                        idsExcept.push(status.data[j]);
                }
            }
        }
    }
    if (types[e.target.name] !== undefined) {
        types[e.target.name].checked = !types[e.target.name].checked;
    }
    for (let i = 0; i < checkboxes.length; i++) {
        let checkbox = checkboxes[i];
        let type = types[checkbox.name];
        if (types[checkbox.name] !== undefined) {
            if (!type.checked) {
                for (let j = 0; j < type.data.length; j++) {
                    if (!idsExcept.includes(type.data[j]))
                        idsExcept.push(type.data[j]);
                }
            }
        }
    }
    for (let i = 0; i < markers.length; i++) {
        let marker = markers[i];
        if (marker.checked && idsExcept.includes(marker.id)) {
            marker.checked = false;
            marker.marker.setMap(null);
        }
        if (!marker.checked && !idsExcept.includes(marker.id)) {
            marker.checked = true;
            marker.marker.setMap(map);
        }
    }
    idsExcept = [];
}

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: {lat: 49.04, lng: 31.45},
    });
    const request = new XMLHttpRequest();
    const url = "/backend/maps";

    if (!window.location.href.includes('/maps')) {
        return;
    }
    // const params = "cart=" + cart;
    request.responseType = "text";
    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // request.send(params);
    request.send();
    request.onload = function () {
        const response = JSON.parse(request.response);
        if (response.success) {
            markers = response.data;
            statuses = response.statuses;
            types = response.types;
            setMarkers(map);
            setCounts();
        }
    };
}

function setMarkers(map) {
    for (let i = 0; i < markers.length; i++) {
        let item = markers[i];
        item.marker = new google.maps.Marker({
            position: {lat: parseFloat(item.lat), lng: parseFloat(item.lng)},
            map,
            title: item.username,
            icon: colors[item.status]
        });
        statuses[item.status].data.push(item.id);
        types[item.type].data.push(item.id);
    }
}

function setCounts() {
    for (let i = 0; i < checkboxes.length; i++) {
        let checkbox = checkboxes[i];
        let span = checkbox.parentNode.querySelector('span');
        if (statuses[checkbox.name] !== undefined) {
            span.textContent += ' (' + statuses[checkbox.name].data.length + ')';
        }
        if (types[checkbox.name] !== undefined) {
            span.textContent += ' (' + types[checkbox.name].data.length + ')';
        }
    }
}