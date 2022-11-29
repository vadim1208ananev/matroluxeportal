<?php

use yii\web\View;

$this->registerJsFile(
    'https://maps.googleapis.com/maps/api/js?key=AIzaSyDhwebuNT8KmXrNxFUa_bWRt14y2OScgMo&callback=initMap&libraries=&v=weekly',
    [
        'position' => View::POS_END,
        'async' => true
    ]
);

$this->params['breadcrumbs'] = [
    [
        'label' => 'Карта торговых точек',
    ],
];

?>
<div class="section">
    <div class="markers">
        <h2 class="has-text-weight-bold">Статусы ТТ</h2>
        <label class="checkbox">
            <input type="checkbox" name="Активные дистрибуция" checked>
            <i class="fas fa-map-marker-alt" style="color: blue;"></i>
            <span>Активные дистрибуция</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Активные, под заказ" checked>
            <i class="fas fa-map-marker-alt" style="color: green;"></i><span>Активные, под заказ</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="НЕ– Активные Дистрибуция (потенциал)" checked>
            <i class="fas fa-map-marker-alt" style="color: yellow;"></i><span>Не активные (потенциал)</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="ТТ закрылась" checked>
            <i class="fas fa-map-marker-alt" style="color: red;"></i><span>ТТ закрылась</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Не заполнен (Статус)" checked>
            <i class="fas fa-map-marker-alt" style="color: purple;"></i><span>Не заполнен</span>
        </label>
    </div>
    <br>
    <div class="markers">
        <h2 class="has-text-weight-bold">Типы ТТ</h2>
        <label class="checkbox">
            <input type="checkbox" name="Мебельный магазин" checked>
            <span></i>Мебельный магазин</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Гаражник" checked>
            <span></i>Гаражник</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="ТРЦ" checked>
            <span></i>ТРЦ</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Производители кроватей, мебели" checked>
            <span></i>Производители кроватей, мебели</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Мебельный салон" checked>
            <span></i>Мебельный салон</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Специализированный магазин корпусной мебели" checked>
            <span></i>Специализированный магазин корпусной мебели</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Склад" checked>
            <span></i>Склад</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Специализированный магазин кровати и матрасы" checked>
            <span></i>Специализированный магазин кровати и матрасы</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Точка на базаре" checked>
            <span></i>Точка на базаре</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Турбазы, отели" checked>
            <span></i>Турбазы, отели</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Интернет магазин" checked>
            <span></i>Интернет магазин</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Контейнер" checked>
            <span></i>Контейнер</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Гипермаркет" checked>
            <span></i>Гипермаркет</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Специализированный магазин мягкой мебели" checked>
            <span></i>Специализированный магазин мягкой мебели</span>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="Не заполнен (Тип)" checked>
            <span></i>Не заполнен</span>
        </label>
    </div>

    <div id="map" style="height: 60em; width: auto"></div>
</div>
