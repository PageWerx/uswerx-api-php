<?php

namespace Pagewerx\UswerxApiPhp\DraftOrder;

class DraftOrder
{
    private $uswerxId;
    public $DraftOrder;
    public $shopData;
    private $shopId;
    private $name;

    public function __construct(int $id, object $data)
    {
        $this->uswerxId = $id;
        $this->DraftOrder = $data;
        $this->shopData = $this->DraftOrder->shop_data;
        $this->shopId = $data->id;
        $this->name = $this->DraftOrder->shop_data->name;
    }

    public function getShopId()
    {
        return $this->shopId;
    }

    public function getUswerxId()
    {
        return $this->uswerxId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the raw draft order data.
     *
     * @return object The draft order data as an object.
     */
    public function getDraftOrderData(): object
    {
        return $this->DraftOrder;
    }

    public function getInvoiceUrl(): string
    {
        return $this->shopData->invoice_url;
    }

    public function getLineItems(): array
    {
        return $this->shopData->line_items;
    }
}