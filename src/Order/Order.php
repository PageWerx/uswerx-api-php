<?php

namespace src\Order;

class Order
{

    private $shopData;
    private $shopId;
    private $name;

    public function __construct(object $shopData)
    {
        $this->shopData = $shopData;
        $this->shopId = $shopData->id;
        $this->name = $shopData->name;
    }
}