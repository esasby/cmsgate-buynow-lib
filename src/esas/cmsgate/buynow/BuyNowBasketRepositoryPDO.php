<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use PDO;

class BuyNowBasketRepositoryPDO extends BuyNowBasketRepository
{
    /**
     * @var PDO
     */
    protected $pdo;
    protected $basketTable;

    const COLUMN_ID = 'id';
    const COLUMN_SHOP_CONFIG_ID = 'shop_config_id';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_ACTIVE = 'active';
    const COLUMN_ASK_PHONE = 'ask_phone';
    const COLUMN_ASK_EMAIL = 'ask_email';
    const COLUMN_ASK_FIO = 'ask_fio';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_CHECKOUT_COUNT = 'checkout_count';

    public function __construct($pdo, $basketTable = null)
    {
        parent::__construct();
        $this->pdo = $pdo;
        if ($basketTable != null)
            $this->basketTable = $basketTable;
        else
            $this->basketTable = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_basket';
    }

    public function saveOrUpdate($basket) {
        if (!empty($basket->getId())) {
            $sql = "select * from $this->basketTable where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $basket->getId(),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $uuid = $row[self::COLUMN_ID];
                $this->logger->info("Updating basket with id[" . $uuid . "]");
                $sql = "UPDATE $this->basketTable set active = :active, name = :name, description = :description, ask_phone = :ask_phone, ask_email = :ask_email, ask_fio = :ask_fio where id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $basket->getId(),
                    self::COLUMN_ACTIVE => $basket->isActive(),
                    self::COLUMN_NAME => $basket->getName(),
                    self::COLUMN_DESCRIPTION => $basket->getDescription(),
                    self::COLUMN_ASK_PHONE => $basket->isAskPhone(),
                    self::COLUMN_ASK_EMAIL => $basket->isAskEmail(),
                    self::COLUMN_ASK_FIO => $basket->isAskFIO(),
                ]);
                return $uuid;
            }
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->basketTable (id, shop_config_id, name, description, active, ask_phone, ask_email, ask_fio, checkout_count, created_at) VALUES (:id, :shop_config_id, :name, :description, :active, :ask_phone, :ask_email, :ask_fio, 0, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            self::COLUMN_SHOP_CONFIG_ID => $basket->getShopConfigId(),
            self::COLUMN_ACTIVE => $basket->isActive(),
            self::COLUMN_NAME => $basket->getName(),
            self::COLUMN_DESCRIPTION => $basket->getDescription(),
            self::COLUMN_ASK_PHONE => $basket->isAskPhone(),
            self::COLUMN_ASK_EMAIL => $basket->isAskEmail(),
            self::COLUMN_ASK_FIO => $basket->isAskFIO(),
        ]);
        $this->logger->info("Basket was saved by id[" . $uuid . "]");
        return $uuid;
    }

    public function getById($basketId) {
        $sql = "select * from $this->basketTable where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $basketId,
        ]);
        $product = null;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $product =  $this->createBasketObject($row);
        }
        return $product;
    }

    public function getByShopConfigId($shopConfigId) {
        $sql = "select * from $this->basketTable where shop_config_id = :shop_config_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'shop_config_id' => $shopConfigId,
        ]);
        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $products[] =  $this->createBasketObject($row);
        }
        return $products;
    }

    private function createBasketObject($row) {
        $basket = new BuyNowBasket();
        $basket
            ->setId($row[self::COLUMN_ID])
            ->setShopConfigId($row[self::COLUMN_SHOP_CONFIG_ID])
            ->setName($row[self::COLUMN_NAME])
            ->setCreatedAt($row[self::COLUMN_CREATED_AT])
            ->setActive($row[self::COLUMN_ACTIVE]) //todo convert to boolean
            ->setCheckoutCount($row[self::COLUMN_CHECKOUT_COUNT])
            ->setItems(BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketId($row[self::COLUMN_ID]));
        return $basket;
    }

    public function incrementCheckoutCount($basketId) {
        $sql = "UPDATE $this->basketTable set checkout_count = checkout_count + 1 where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $basketId
        ]);
    }
}