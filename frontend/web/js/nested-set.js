document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    function handleCaretsEvents(item) {
        let el = item.parentElement.querySelector(".nested");
        if (el) el.classList.toggle("active");
        item.classList.toggle("caret-down");
    }

    function addInnerElement(item) {
        let html = '';
        if (item.parentElement.querySelector('ul.nested') != null) return;
        if (item.parentElement.querySelector('.radio') != null) return;

        let data = '_csrf-frontend=' + yii.getCsrfToken() +
            '&id=' + encodeURIComponent(item.dataset.id);
        ajax.call(item, 'POST', '/product/get-nested-set-children', data, function (response) {
            if (response.success === false) {
                console.log(JSON.parse(response.data));
            }

            let classList = item.closest('.form-group').classList.toString();
            let match = classList.match(/(field-wardrobedoorform-door)(\d?)/);
            let idx = match[2];

            if (response.hasLeaves == false) {
                html = `<ul class="nested active">`;
                html += JSON.parse(response.data).map(function (item) {
                    return `<li><span class="caret" data-id="${item.id}" data-1c_id="${item['1c_id']}">${item.name}</span></li>`;

                    // <li><span className="caret" data-id="<?= $child['id'] ?>"
                    //           data-1c_id="<?= $child['1c_id'] ?>"
                    //           data-is-leaf="<?= ($child->rgt - $child->lft) == 1 ?>"><?= $child->name ?></span>
                    // </li>

                }).join('');
                html += `</ul>`;
                item.insertAdjacentHTML('beforeend', html);
            } else {
                html = '<ul class="nested active">';
                html += JSON.parse(response.data).map(function (item) {
                    return `
                    <li class="radio">
                        <div class="radio">
                            <img src="${item.path}" alt="${item.name}" data-show-image="${item.showImage}">
                            <label>
                                <input type="radio" name="WardrobeDoorForm[door${idx}]" value="${item.name}"> ${item.name}
                            </label>
                        </div>
                    </li>`;
                }).join('');
                html += '</ul>';
                item.insertAdjacentHTML('afterend', html);
            }
        });
    }

    //use event delegation to handle the event
    document.addEventListener('click', function (e) {
        let el;
        if (e.target && e.target.classList.contains('caret')) {
            addInnerElement(e.target);
            handleCaretsEvents(e.target);
        }

        let label = e.target.closest('label');
        if (label) {
            el = label.closest('.wardrobedoorform-door');
            if (el) el.parentElement.querySelector('.help-block').innerHTML = '';
        }
    });

    document.querySelector('.product__wardrobe-cart')?.addEventListener('click', function (e) {
        let el;
        e.preventDefault();

        let data = '_csrf-frontend=' + yii.getCsrfToken()
            + '&WardrobeDoorForm=' + $(document.querySelector('#wardrobe-form')).serialize();
        ajax.call(this, 'POST', window.location.pathname, data, function (response) {
            response = JSON.parse(response);
            if (response.success == false) {
                //validation failed
                for (let key in response.error) {
                    el = document.getElementById(key);
                    el.parentElement.querySelector('.help-block').textContent = response.error[key][0];
                }
                return;
            }

            let data = response.data;
            // 1 500, 450, 2 400, ВЕНГЕ, чорний браш БАВАРІЯ, Б12 КЛ, Б12 КЛ
            let sizeArray = [
                data.width,
                data.depth,
                data.height,
                data.boardColor,
                data.profileColor,
                data.door1
                // (data.sameDoors) == true ? data.door1 : data.door2
            ];
            if (data.numberOfDoors >= 2)
                sizeArray.push((data.sameDoors) == true ? data.door1 : data.door2);
            if (data.numberOfDoors >= 3)
                sizeArray.push((data.sameDoors) == true ? data.door1 : data.door3);
            if (data.numberOfDoors >= 4)
                sizeArray.push((data.sameDoors) == true ? data.door1 : data.door4);
            let sizeId = sizeArray.join(', ');

            let productData = {
                'productId': data.productId,
                'sizeId': sizeId,
                'amount': 1,
            }

            addIsLoading(addToCartSpec, [this, productData, 'cart']);
        });
    });

    document.querySelector('.different-product__cart')?.addEventListener('click', function (e) {
        e.preventDefault();
        let el;
        let sizeArray = [];

        let data = '_csrf-frontend=' + yii.getCsrfToken()
            + '&DifferentProductForm=' + $(document.querySelector('#different-product-form')).serialize();
        ajax.call(this, 'POST', window.location.pathname, data, function (response) {
            response = JSON.parse(response);
            if (response.success == false) {
                //validation failed
                for (let key in response.error) {
                    el = document.getElementById(key);
                    el.parentElement.querySelector('.help-block').textContent = response.error[key][0];
                }
                return;
            }

            for (let key in response.data) {
                if (['productId', 'requiedFields'].includes(key))
                    continue;
                if (response.data[key] == null)
                    continue;
                sizeArray.push(response.data[key]);
            }

            let sizeId = sizeArray.join(', ');

            let productData = {
                'productId': response.data.productId,
                'sizeId': sizeId,
                'amount': 1,
            }

            addIsLoading(addToCartSpec, [this, productData, 'cart']);
        });
    });

});