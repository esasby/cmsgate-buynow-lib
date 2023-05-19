<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\dao\OrderRepository;
use esas\cmsgate\bridge\dao\OrderRepositoryPDO;
use esas\cmsgate\bridge\dao\OrderStatusBridge;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use PDO;

class OrderRepositoryBuyNow extends OrderRepositoryPDO
{
    const COLUMN_BASKET_ID = 'basket_id';
    const COLUMN_EXPIRES_AT = 'expires_at';

    /**
     * @return $this
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(OrderRepository::class, new OrderRepositoryBuyNow());
    }

    /**
     * @param $order OrderBuyNow
     * @param $configId
     * @return string
     */
    public function add($order)
    {
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->tableName (id, config_id, basket_id, expires_at, created_at, order_data, order_data_hash, status) VALUES (:id, :config_id, :basket_id, :expires_at, CURRENT_TIMESTAMP, :order_data, :order_data_hash, 'new')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            self::COLUMN_ID => $uuid,
            self::COLUMN_CONFIG_ID => $order->getShopConfigId(),
            self::COLUMN_BASKET_ID => $order->getBasketId(),
            self::COLUMN_EXPIRES_AT => empty($order->getExpiresAt()) ? null : $order->getExpiresAt()->format('Y-m-d H:i:s'),
            self::COLUMN_ORDER_DATA => $this->encodeOrderData($order->getOrderData()),
            self::COLUMN_ORDER_DATA_HASH => self::hashData($order->getOrderData()),
        ]);
        return $uuid;
    }

    protected function encodeOrderData($orderData) {
        return json_encode($orderData);
    }

    protected function decodeOrderData($orderData) {
        $orderDataArray = json_decode($orderData, true);
        $orderDataBuyNow = new OrderDataBuyNow();
        $orderDataBuyNow
            ->setOrderId($orderDataArray['orderId'])
            ->setCurrency($orderDataArray['currency'])
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
    }

    protected function createOrderObject($row)
    {
        $order = new OrderBuyNow();
        $order
            ->setId($row[self::COLUMN_ID])
            ->setShopConfigId($row[self::COLUMN_CONFIG_ID])
            ->setStatus($row[self::COLUMN_STATUS])
            ->setExtId($row[self::COLUMN_EXT_ID])
            ->setBasketId($row[self::COLUMN_BASKET_ID])
            ->setOrderData($this->decodeOrderData($row['order_data']))
            ->setCreatedAt($row[self::COLUMN_CREATED_AT]);
        return $order;
    }

    public function countByBasketId($basketId) {
        $sql = "select count(id) from $this->tableName where basket_id = :basket_id and (status = :status or expires_at is null or expires_at > now())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            self::COLUMN_BASKET_ID => $basketId,
            self::COLUMN_STATUS => OrderStatusBridge::payed()->getOrderStatus(),
        ]);
        $count = 0;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $count = $row[0];
        }
        return $count;
    }
}