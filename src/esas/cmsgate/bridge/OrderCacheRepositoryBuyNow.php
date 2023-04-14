<?php


namespace esas\cmsgate\bridge;

class OrderCacheRepositoryBuyNow extends OrderCacheRepositoryPDO
{
//    protected $serializer;
//
//    public function __construct($pdo, $tableName = null) {
//        parent::__construct($pdo, $tableName);
//        $encoder = [new JsonEncoder()];
//        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
//        $normalizer = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, $extractor)];
//        $this->serializer = new Serializer($normalizer, $encoder);
//    }


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