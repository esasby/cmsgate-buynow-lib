<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use PDO;

class BuyNowBasketItemRepositoryPDO extends BuyNowBasketItemRepository
{
    /**
     * @var PDO
     */
    protected $pdo;
    protected $table;

    const COLUMN_ID = 'id';
    const COLUMN_BASKET_ID = 'basket_id';
    const COLUMN_PRODUCT_ID = 'product_id';
    const COLUMN_COUNT = 'count';
    const COLUMN_MAX_COUNT = 'max_count';
    const COLUMN_CREATED_AT = 'created_at';

    public function __construct($pdo, $table = null)
    {
        parent::__construct();
        $this->pdo = $pdo;
        if ($table != null)
            $this->table = $table;
        else
            $this->table = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_basket_item';
    }

    public function saveOrUpdate($basketItem) {
        if (!empty($basketItem->getId())) {
            $sql = "select * from $this->table where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $basketItem->getId(),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $uuid = $row[self::COLUMN_ID];
                $this->logger->info("Updating basket item with id[" . $uuid . "]");
                $sql = "UPDATE $this->table set count = :count, max_count = :max_count where id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $basketItem->getId(),
                    self::COLUMN_COUNT => $basketItem->getCount(),
                    self::COLUMN_MAX_COUNT => $basketItem->getMaxCount(),
                ]);
                return $uuid;
            }
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->table (id, basket_id, product_id, count, max_count, created_at) VALUES (:id, :basket_id, :product_id, :count, :max_count, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            self::COLUMN_BASKET_ID => $basketItem->getBasketId(),
            self::COLUMN_PRODUCT_ID => $basketItem->getProductId(),
            self::COLUMN_COUNT => $basketItem->getCount(),
            self::COLUMN_MAX_COUNT => $basketItem->getMaxCount(),
        ]);
        $this->logger->info("Basket item was saved with id[" . $uuid . "]");
        return $uuid;
    }

    public function getById($basketItemId) {
        $sql = "select * from $this->table where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $basketItemId,
        ]);
        $basketItem = null;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $basketItem =  $this->createBasketItemObject($row);
        }
        return $basketItem;
    }

    public function getByBasketId($basketId) {
        $sql = "select * from $this->table where basket_id = :basket_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'basket_id' => $basketId,
        ]);
        $items = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $items[] =  $this->createBasketItemObject($row);
        }
        return $items;
    }

    public function deleteById($basketItemId) {
        $sql = "delete from $this->table where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $basketItemId,
        ]);
    }

    public function deleteByProductId($productId) {
        $sql = "delete from $this->table where product_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $productId,
        ]);
    }

    public function deleteByBasketId($basketId) {
        $sql = "delete from $this->table where basket_id = :basket_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'basket_id' => $basketId,
        ]);
    }

    private function createBasketItemObject($row) {
        $basketItem = new BuyNowBasketItem();
        $basketItem
            ->setId($row[self::COLUMN_ID])
            ->setBasketId($row[self::COLUMN_BASKET_ID])
            ->setBasket(BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($row[self::COLUMN_BASKET_ID]))
            ->setProductId($row[self::COLUMN_PRODUCT_ID])
            ->setProduct(BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getById($row[self::COLUMN_PRODUCT_ID]))
            ->setCount($row[self::COLUMN_COUNT])
            ->setMaxCount($row[self::COLUMN_MAX_COUNT])
            ->setCreatedAt($row[self::COLUMN_CREATED_AT])
        ;
        return $basketItem;
    }

    public function getTableName() {
        return $this->table;
    }
}