<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\dao\OrderCacheRepositoryPDO;

class OrderCacheRepositoryBuyNow extends OrderCacheRepositoryPDO
{
    protected function encodeOrderData($orderData) {
        return json_encode($orderData);
    }

    protected function decodeOrderData($orderData) {
        $orderDataArray = json_decode($orderData, true);
        $orderDataBuyNow = new OrderDataBuyNow();
        $orderDataBuyNow
            ->setOrderId($orderDataArray['orderId'])
            ->setCurrency($orderDataArray['currency'])
            ->setBasketId($orderDataArray['basketId'])
            ->setAmount($orderDataArray['amount'])
            ->setCustomerPhone($orderDataArray['customerPhone'])
            ->setCustomerEmail($orderDataArray['customerEmail'])
            ->setCustomerFIO($orderDataArray['customerFIO']);
        foreach ($orderDataArray['items'] as $itemArray) {
            $item = new OrderDataItemBuyNow();
            $item
                ->setSku($itemArray['sku'])
                ->setProductId($itemArray['productId'])
                ->setPrice($itemArray['price'])
                ->setCount($itemArray['count'])
                ->setName($itemArray['name']);
            $orderDataBuyNow->addItem($item);
        }

        return $orderDataBuyNow;
//        return $this->serializer->deserialize($orderData, OrderDataBuyNow::class, 'json');
    }

}