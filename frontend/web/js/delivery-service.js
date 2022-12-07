document.addEventListener('DOMContentLoaded', () => {

    let s;
    let deliveryService = {

        settings: {
            serviceType: document.querySelectorAll('.delivery__container .service-type input'),
            searchFields: document.querySelectorAll('.city, .warehouse, .street'),
            warehouses: document.querySelectorAll('.warehouse'),
            product: document.querySelector('.product'),
            sameAddress: document.querySelector('.same-address'),
            afterComplaint: document.querySelector('.after-complaint'),
        },

        init: function () {
            s = this.settings;
            this.bindActions();
        },

        bindActions: function () {
            ael(s.serviceType, 'click', this.handleServiceType);
            ael(s.searchFields, 'input', function () {
                let self = this;
                setTimeout(deliveryService.handleSearch.bind(self), 1000);
            });
            ael(document, 'click', this.handleClickSearch);
            ael(s.warehouses, 'focus', this.handleWarehouseFocus);
         //   ael(s.product, 'click', this.handleClickProduct);
         ael(s.product, 'change', this.handleClickProduct);
            ael(s.sameAddress, 'click', this.handleClickSameAddress);
            document.querySelectorAll('.delivery__container .service-type input:checked').forEach((elem => deliveryService.handleServiceType.call(elem)));
            deliveryService.handleClickSameAddress.call(s.sameAddress);
        },

        handleServiceType: function () {
            let self = this;
            let parent = self.closest('.delivery__container');
            let warehouse = parent.querySelector('.warehouse');
            let street = parent.querySelector('.street');
            let building = parent.querySelector('.building');
            let flat = parent.querySelector('.flat');

            let warehouseRef = parent.querySelector('.warehouse-ref');
            let streetRef = parent.querySelector('.street-ref');

            if (self.value == 'WarehouseWarehouse') {
                warehouse.style.display = 'block';
                street.style.display = 'none';
                building.style.display = 'none';
                flat.style.display = 'none';
            } else if (self.value == 'WarehouseDoors') {
                warehouse.style.display = 'none';
                street.style.display = 'block';
                building.style.display = 'block';
                flat.style.display = 'block';
            }
            warehouse.value = '';
            street.value = '';
            building.value = '';
            flat.value = '';
            warehouseRef.value = '';
            streetRef.value = '';
        },

        handleWarehouseFocus: function () {
            deliveryService.handleSearch.call(this);
        },

        handleSearch: function (e) {
            let html = '';
            let div = document.createElement('div');
            let input = this.querySelector('input');
            // let coords = getCoords(self);

            if (this.classList.contains('city')) {
                if (this.value.length <= 2)
                    return;
                // searchContainer.querySelector('.search__warehouse').value = '';
                // searchContainer.querySelector('.search__street').value = '';

                let data = '_csrf-frontend=' + yii.getCsrfToken() +
                    '&city=' + encodeURIComponent(this.value) +
                    '&deliveryServiceId=' + encodeURIComponent(this.closest('.delivery__container').querySelector('input[name^="delivery_serv"]:checked').value);
                // searchContainer.querySelector('.column[data-search-handler="city"] .control').classList.add('is-loading');
                this.closest('.control').classList.add('is-loading');
                div.className = 'letter-search letter-search__city';
                ajax.call(this, 'POST', '/cart/get-cities', data, function (response) {
                    this.closest('.control').classList.remove('is-loading');
                    this.closest('div').querySelectorAll('.letter-search')?.remove();
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
                    this.parentElement.append(div);
                });
            } else if (this.classList.contains('warehouse')) {
                let data = '_csrf-frontend=' + yii.getCsrfToken() +
                    '&cityRef=' + encodeURIComponent(this.closest('.delivery__container').querySelector('.city-ref').value) +
                    '&deliveryServiceId=' + encodeURIComponent(this.closest('.delivery__container').querySelector('input[name^="delivery_serv"]:checked').value);
                this.closest('.control').classList.add('is-loading');
                div.className = 'letter-search letter-search__street';
                ajax.call(this, 'POST', '/cart/get-warehouses', data, function (response) {
                    this.closest('.control').classList.remove('is-loading');
                    this.closest('div').querySelectorAll('.letter-search')?.remove();
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
                    this.parentElement.append(div);
                });
            } else if (this.classList.contains('street')) {
                if (this.value.length <= 2)
                    return;
                // searchContainer.querySelector('.search__warehouse').value = '';
                // searchContainer.querySelector('.search__street').value = '';
                let data = '_csrf-frontend=' + yii.getCsrfToken() +
                    '&cityRef=' + encodeURIComponent(this.closest('.delivery__container').querySelector('.city-ref').value) +
                    '&street=' + encodeURIComponent(this.value) +
                    '&deliveryServiceId=' + encodeURIComponent(this.closest('.delivery__container').querySelector('input[name^="delivery_serv"]:checked').value);
                this.closest('.control').classList.add('is-loading');
                div.className = 'letter-search letter-search__street';
                ajax.call(this, 'POST', '/cart/get-streets', data, function (response) {
                    this.closest('.control').classList.remove('is-loading');
                    this.closest('div').querySelectorAll('.letter-search')?.remove();
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
                    this.parentElement.append(div);
                });
            }
        },

        handleClickSearch: function (e) {
            let input;
            let self = e.target;
            if (self && !self.classList.contains('letter-search__not-found')) {
                if (self.classList.contains('letter-search__city-item')) {
                    input = self.closest('.control').querySelector('input');
                    input.value = self.textContent;
                    input.dataset.ref = self.dataset.ref;
                    self.closest('.delivery__container').querySelector('.city-ref').value = e.target.dataset.ref;
                    self.parentElement.remove();
                } else if (self.classList.contains('letter-search__warehouse-item')) {
                    input = self.closest('.control').querySelector('input');
                    input.value = self.textContent;
                    input.dataset.ref = self.dataset.ref;
                    self.closest('.delivery__container').querySelector('.warehouse-ref').value = e.target.dataset.ref;
                    self.parentElement.remove();
                } else if (self.classList.contains('letter-search__street-item')) {
                    input = self.closest('.control').querySelector('input');
                    input.value = self.textContent;
                    input.dataset.ref = self.dataset.ref;
                    self.closest('.delivery__container').querySelector('.street-ref').value = e.target.dataset.ref;
                    self.parentElement.remove();
                }
            }
        },

        handleClickProduct: function () {
            let html = '';
            let data = '_csrf-frontend=' + yii.getCsrfToken() +
                '&productId=' + encodeURIComponent(this.value);
            this.closest('.control').classList.add('is-loading');
            ajax.call(this, 'POST', '/product/get-product-sizes', data, function (response) {
                this.closest('.control').classList.remove('is-loading');
                if (response.success === true) {
                    let data = response.data[this.value];
                    for (let key in data) {
                        html += `<option value="${data[key].size_id}">${data[key].name}</option>`;
                    }
                }
                document.querySelector('.size').innerHTML = html;
            });
        },

        handleClickSameAddress: function () {
            // let afterComplaint = document.querySelector('.after-complaint');
            if (this.checked) {
                s.afterComplaint.style.pointerEvents = 'none';
                s.afterComplaint.style.opacity = '0.4';
            } else {
                s.afterComplaint.style.pointerEvents = 'initial';
                s.afterComplaint.style.opacity = '1';
            }
        },

    };

    (function () {
        deliveryService.init();
    })();
    //product_cm

});
var select_cm = document.querySelector('.product_cm');
var attrs_data = document.querySelector('#data_attrs');
select_cm.addEventListener('change', function (event) {
    attrs_data.innerHTML = '';
    var selected_cm_value = event.target.value
    if (selected_cm_value == 0) {
        alert('Выберите доступный вариант');
        return;
    }
    var formdata = new FormData();
    var csrf_token = yii.getCsrfToken();
    formdata.append("selected_cm_value", selected_cm_value)
    formdata.append("_csrf-frontend", csrf_token)
    var requestOptions = {
        method: 'POST',
        body: formdata
    }
    fetch('/product/get-product-attributes', requestOptions)
        .then(res =>
            res.json()
        ).then(res => {
            if (res.success !== true) {
                console.log(res)
                attrs_data.innerHTML = 'error';
                return;
            }
            html_attr = '';
            for (var key in res.data) {
                html_attr += '<div class="column attr-column">';
                html_attr += `<div class="attr-group-title">${key}</div>`;
                html_attr += '<div class="select field control">';
                html_attr += '<div class="form-group field-complaintform-size_id">';

                html_attr += '<select id="complaintform-size_id" class="input is-primary size" name="attr_ids[]">';
                for (var attr_id in res.data[key]) {
                    html_attr += `<option value="${attr_id}">${res.data[key][attr_id]}</option>`;
                }
                html_attr += '</select>';
                html_attr += ' <p class="help-block help-block-error"></p>';
                html_attr += '</div>';
                html_attr += ' </div>';
                html_attr += ' </div>';
            }




            attrs_data.innerHTML = html_attr;

            console.log(res)
        }
        )

})