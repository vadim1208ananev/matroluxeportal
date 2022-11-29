<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Доставка и оплата';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container content section">
    <h1><?= Html::encode($this->title) ?></h1>
    <section class="main">
        <section class="wrapper-1200">
            <article class="">
                <h2><span style="color:#ff6666"><strong>Вы можете совершить оплату вашего заказа при получении!</strong></span>
                </h2>
                <ul>
                    <li><span style="font-size:16px"><span style="color:rgb(0, 0, 0)">Оплата при получении товара на складе Матролюкс самовывозом:</span></span>
                    </li>
                </ul>
                <p><span style="font-size:16px">г. Днепр, ул. Николая Руденка, 53</span></p>
                <p><span style="font-size:16px">г. Киев, ул. Красноткацкая, 46</span></p>
                <p><span style="font-size:16px">г. Одесса, Николая Боровского 37 (завод Микрон)</span></p>
                <p><span style="font-size:16px">г. Львов , ул. Богдана Хмельницкого 302 ( заезд с ул. Галицькой от Галицкого базара )</span>
                </p>
                <p><span style="font-size:16px">г. Ивано-Франковск ул. Левинского, 1</span></p>
                <ul>
                    <li><span style="font-size:16px"><span style="color:rgb(0, 0, 0)">Наложенный платеж - оплата в отделении курьерской службы доставки (комиссия +45 грн. и&nbsp;2% от суммы платежа).&nbsp;</span></span>
                    </li>
                </ul>
                <h2>&nbsp;</h2>
                <h2><strong>Доставка осуществляется курьерскими службами </strong></h2>
                <p style="text-align:center">&nbsp;</p>
                <p><span style="font-size:16px"><strong>Сроки доставки - 2-5 дней из Днепра</strong></span></p>
                <ul>
                    <li><span style="font-size:16px">&nbsp;<span style="color:#000000">Самовывоз со склада в Днепре - бесплатно с понедельника по пятницу с 9-00 до 18-00.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки малогабаритных товаров (одеяла, подушки, наматрасники, маленькие полочки и т.д.) - от 150 грн. за 1 единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки среднегабаритных товаров (каркасы, бескаркасная мебель, столы и т.д.) - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:#000000">250 грн. за 1 единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки крупногабаритных товаров (шкафы, мебель) шириной до 1800 мм - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:#000000">550 грн. за 1 единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:rgb(0, 0, 0)">&nbsp;Стоимость доставки крупногабаритных товаров (шкафы, мебель) шириной более 1800 мм - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span
                                    style="color:rgb(0, 0, 0)">550 грн. за 1 единицу.</span></span></li>
                    <li><span style="font-size:16px"><span style="color:rgb(0, 0, 0)">&nbsp;Стоимость доставки крупногабаритных товаров (</span><span
                                    style="color:rgb(0, 0, 0)">диваны</span><span
                                    style="color:rgb(0, 0, 0)">)&nbsp; - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:rgb(0, 0, 0)">80</span><span
                                    style="color:rgb(0, 0, 0)">0 грн. за 1 единицу.</span></span></li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки матрасов шириной до 1 метра - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:#000000">250 грн. за 1 единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки матрасов шириной свыше 1 метра - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span
                                    style="color:#000000">450&nbsp;грн. за 1 единицу.</span></span></li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки футонов, детских матрасов, matro-roll (скрутка) шириной до 1 метра - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:#000000">180 грн за 1 единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость доставки футонов, детских матрасов, matro-roll (скрутка) шириной свыше 1 метра - </span></span><span
                                style="color:rgb(0, 0, 0); font-size:16px">от&nbsp;</span><span
                                style="font-size:16px"><span style="color:#000000">250 грн за единицу.</span></span>
                    </li>
                    <li><span style="font-size:16px">Доставка осуществляется по адресу до подъезда дома или ворот двора. Подъем на этаж и/или занос в квартиру не осуществляется.</span>
                    </li>
                    <li><span style="font-size:16px">При условии доставки до конечного потребителя в удаленный населенный пункт стоимость доставки увеличивается на 20%.</span>
                    </li>
                    <li><span style="font-size:16px">​При заказе на сумму до 5000 грн - стоимость доставки согласно указанным правилам</span>
                    </li>
                    <li><span style="font-size:16px">При заказе на сумму от 5000 грн - доставка бесплатная</span></li>
                </ul>
                <p><span style="color:#A52A2A"><span style="font-size:18px">Правила получения товара при курьерской доставке</span></span>
                </p>
                <p><span style="font-size:16px"><strong><span style="color:#696969"><span
                                        style="font-family:arial,helvetica,sans-serif">В случае доставки товара курьерской службой в момент получения товара, перед тем, как подписать товарно - транспортную накладную (далее - ТТН), Покупатель обязательно должен проверить целостность упаковки.</span></span></strong></span>
                </p>
                <p><br>
                    <span style="font-size:16px"><strong><span style="color:#696969"><span
                                        style="font-family:arial,helvetica,sans-serif">Если упаковка повреждена (поцарапана, потерта, порвана, надорвана, мята, промокла, в коробке дыры и т.п.) или не повреждена, Покупатель раскрывает ее и убеждается, что товар целый (не поврежден).</span></span></strong></span>
                </p>
                <p><br>
                    <span style="font-size:16px"><strong><span style="color:#696969"><span
                                        style="font-family:arial,helvetica,sans-serif">Количество изделий и отсутствие на них повреждений Покупателю необходимо проверить в присутствии курьера.<br>
Только после того, как Покупатель убедится, что товар не поврежден, он подписывает накладную курьерской службы (ТТН). Если, осуществляя осмотр, Покупатель нашел дефект (нарушение целостности упаковки или самого товара) - он требует у работников службы доставки специальный бланк: Акт приема-передачи (составляется в двух экземплярах и описываются все повреждения). Покупатель вправе отказаться от приема поврежденного товара, но акт приема-передачи об отказе должен быть составлен и передан продавцу в течение 1-2 календарных дней. В случае выявления Покупателем повреждений части груза, необходимо отказаться от всего груза, нельзя принять груз частично и отказаться от остатка.</span></span></strong></span>
                </p>
                <p><br>
                    <span style="font-size:16px"><strong><span style="color:#696969"><span
                                        style="font-family:arial,helvetica,sans-serif">В случае невыполнения Покупателем действий описанных выше и принятия им товара в поврежденной упаковке, претензии по качеству товара Продавцом не принимаются.</span></span></strong></span>
                </p>
                <p><span style="color:#B22222"><span style="font-size:18px"><span
                                    style="font-family:arial,helvetica,sans-serif">Стоимость доставки по каждому отдельному заказу просчитывается индивидуально. Точную стоимость уточняйте при заказе у консультанта.</span></span></span>
                </p>
                <h2><strong><strong>Стоимость заноса/подъема в квартиру</strong></strong></h2>
                <p>&nbsp;</p>
                <ul>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;В случае необходимости подъема на этаж покупатель обязан заведомо информировать в каждом конкретном случае.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">&nbsp;Стоимость подъема на этаж - согласно тарифам перевозчика</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">Доставка товара на первый этаж или до лифта оплачивается как один этаж подъема.</span></span>
                    </li>
                </ul>
                <h2>&nbsp;</h2>
                <h2><strong>Оплата</strong></h2>
                <p>&nbsp;</p>
                <p><span style="font-size:16px"><strong><strong>Доставка нестандартного товара производится только после предоплаты 100%</strong></strong><span
                                style="color:#000000">​</span></span></p>
                <ul>
                    <li><span style="font-size:16px"><span style="color:#000000">Наложенный платеж - комиссия 3% от суммы платежа. Оплата в отделении курьерской службы доставки. Этот способ оплаты действителен только при условии заказа стандартных либо акционных моделей, которые есть в наличии на складе.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">Оплата менеджерам при получении товара на складе Матролюкс самовывозом. г. Днепропетровск, </span>ул.&nbsp;Николая Руденка<span
                                    style="color:#000000">, 53 стандартных либо акционных моделей при условии самовывоза с 9-00 до 18-00 Пн-Пт.</span></span>
                    </li>
                    <li><span style="font-size:16px"><span style="color:#000000">Перечисление оплаты или предоплаты за товар на расчетный счет компании Матролюкс для юридических и физических лиц:</span></span>
                    </li>
                </ul>
                <p><span style="font-size:16px"><span style="color:#000000">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span
                                style="color:#0000CD">•</span><span style="color:#000000"> Банковский платеж для физических лиц - будет отправлено письмо с реквизитами для оплаты. Оплату можно производить в любом банке. Для получения счета необходимо предоставить ксерокопию паспорта и ИНН</span></span>
                </p>
                <p><span style="font-size:16px"><span
                                style="color:#000000">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span><span
                                style="color:#0000CD">&nbsp;•</span><span style="color:#000000"> Банковский платеж без НДС - будет выставлен счет, по которому можно произвести оплату за товар в любом банке. Для плательщиков единого налога. Предоставляем необходимый пакет документов. Для получения счета необходимо предоставить весь пакет документов.</span></span>
                </p>
                <div style="color: rgb(34, 34, 34); font-family: arial, sans-serif;"><strong><span
                                style="font-size:16px">Условия доставки действуют при оформлении заказа в интернет-магазине matroluxe.ua</span></strong><span
                            style="font-size:16px"><strong>.</strong></span></div>
                <div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 12.8px;">&nbsp;</div>
                <div style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 12.8px;">&nbsp;</div>
                <p>&nbsp;</p>
                <p><span style="color:#808080"><span style="font-size:20px"><strong>Мы готовы привезти понравившуюся Вам продукцию в любую точку Украины. Покупки с нами максимально просты и удобны!</strong></span></span>
                </p>
                <div class="clear"></div>
            </article>
        </section>
    </section>

</div>