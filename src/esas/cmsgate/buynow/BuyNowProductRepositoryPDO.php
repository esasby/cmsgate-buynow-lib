<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use PDO;

class BuyNowProductRepositoryPDO extends BuyNowProductRepository
{
    /**
     * @var PDO
     */
    protected $pdo;
    protected $tableName;

    const COLUMN_ID = 'id';
    const COLUMN_MERCHANT_ID = 'merchant_id';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_SKU = 'sku';
    const COLUMN_PRICE = 'price';
    const COLUMN_CURRENCY = 'currency';
    const COLUMN_ACTIVE = 'active';
    const COLUMN_CREATED_AT = 'created_at';

    public function __construct($pdo, $tableName = null)
    {
        parent::__construct();
        $this->pdo = $pdo;
        if ($tableName != null)
            $this->tableName = $tableName;
        else
            $this->tableName = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_product';
    }

    public function saveOrUpdate($product) {
        if (!empty($product->getId())) {
            $sql = "select * from $this->tableName where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $product->getId(),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $uuid = $row[self::COLUMN_ID];
                $merchant = $row[self::COLUMN_MERCHANT_ID];
                $this->logger->info("Updating product with id[" . $uuid . "]");
                if ($merchant != $product->getMerchantId())
                    $this->logger->warn('Product merchant can not be changed from[' . $merchant . '] to[' . $product->getMerchantId() . ']');
                $sql = "UPDATE $this->tableName set active = :active, sku = :sku, name = :name, description = :description, price = :price, currency = :currency where id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $product->getId(),
                    self::COLUMN_ACTIVE => $product->isActive(),
                    self::COLUMN_SKU => $product->getSku(),
                    self::COLUMN_NAME => $product->getName(),
                    self::COLUMN_DESCRIPTION => $product->getDescription(),
                    self::COLUMN_PRICE => $product->getPrice(),
                    self::COLUMN_CURRENCY => $product->getCurrency(),
                ]);
                return $uuid;
            }
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->tableName (id, merchant_id, name, description, sku, active, price, currency, created_at) VALUES (:id, :merchant_id, :name, :description, :sku, :active, :price, :currency, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            self::COLUMN_MERCHANT_ID => $product->getMerchantId(),
            self::COLUMN_ACTIVE => $product->isActive(),
            self::COLUMN_SKU => $product->getSku(),
            self::COLUMN_NAME => $product->getName(),
            self::COLUMN_DESCRIPTION => $product->getDescription(),
            self::COLUMN_PRICE => $product->getPrice(),
            self::COLUMN_CURRENCY => $product->getCurrency(),
        ]);
        $this->logger->info("Product was saved by id[" . $uuid . "]");
        return $uuid;
    }

    public function getById($productId) {
        $sql = "select * from $this->tableName where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $productId,
        ]);
        $product = null;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $product =  $this->createProductObject($row);
        }
        return $product;
    }

    public function deleteById($productId) {
        $sql = "delete from $this->table where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $productId,
        ]);
    }

    public function getByMerchantId($merchantId) {
        $sql = "select * from $this->tableName where merchant_id = :merchant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'merchant_id' => $merchantId,
        ]);
        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $products[] =  $this->createProductObject($row);
        }
        return $products;
    }

    private function createProductObject($row) {
        $product = new BuyNowProduct();
        $product
            ->setId($row[self::COLUMN_ID])
            ->setMerchantId($row[self::COLUMN_MERCHANT_ID])
            ->setName($row[self::COLUMN_NAME])
            ->setDescription($row[self::COLUMN_DESCRIPTION])
            ->setSku($row[self::COLUMN_SKU])
            ->setPrice($row[self::COLUMN_PRICE])
            ->setCurrency($row[self::COLUMN_CURRENCY])
            ->setCreatedAt($row[self::COLUMN_CREATED_AT])
            ->setActive($row[self::COLUMN_ACTIVE]);//todo convert to boolean
        return $product;
    }

}