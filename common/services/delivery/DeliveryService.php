<?php

namespace common\services\delivery;

interface DeliveryService
{
    public function getCities();

    public function getWarehouses();

    public function getStreets();

}