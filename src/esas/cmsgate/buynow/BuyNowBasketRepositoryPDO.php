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
    protected $tableName;

    const COLUMN_ID = 'id';
    const COLUMN_SHOP_CONFIG_ID = 'shop_config_id';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_ACTIVE = 'active';
    const COLUMN_ASK_PHONE = 'ask_phone';
    const COLUMN_ASK_EMAIL = 'ask_email';
    const COLUMN_ASK_FIO = 'ask_fio';
    const COLUMN_RETURN_URL = 'return_url';
    const COLUMN_CLIENT_UI_CSS = 'client_ui_css';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_CHECKOUT_COUNT = 'checkout_count';

    public function __construct($pdo, $basketTable = null)
    {
        parent::__construct();
        $this->pdo = $pdo;
        if ($basketTable != null)
            $this->tableName = $basketTable;
        else
            $this->tableName = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_basket';
    }

    public function saveOrUpdate($basket) {
        if (!empty($basket->getId())) {
            $sql = "select * from $this->tableName where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $basket->getId(),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $uuid = $row[self::COLUMN_ID];
                $this->logger->info("Updating basket with id[" . $uuid . "]");
                $sql = "UPDATE $this->tableName set shop_config_id = :shop_config_id, active = :active, name = :name, description = :description, ask_phone = :ask_phone, ask_email = :ask_email, ask_fio = :ask_fio, return_url = :return_url, client_ui_css = :client_ui_css  where id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $basket->getId(),
                    self::COLUMN_ACTIVE => $basket->isActive() ? 1 : 0,
                    self::COLUMN_NAME => $basket->getName(),
                    self::COLUMN_SHOP_CONFIG_ID => $basket->getShopConfigId(),
                    self::COLUMN_DESCRIPTION => $basket->getDescription(),
                    self::COLUMN_ASK_PHONE => $basket->isAskPhone() ? 1 : 0,
                    self::COLUMN_ASK_EMAIL => $basket->isAskEmail() ? 1 : 0,
                    self::COLUMN_ASK_FIO => $basket->isAskFIO() ? 1 : 0,
                    self::COLUMN_RETURN_URL => $basket->getReturnUrl(),
                    self::COLUMN_CLIENT_UI_CSS => $basket->getClientUICss(),
                ]);
                return $uuid;
            }
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->tableName (id, shop_config_id, name, description, active, ask_phone, ask_email, ask_fio, return_url, client_ui_css, checkout_count, created_at) VALUES (:id, :shop_config_id, :name, :description, :active, :ask_phone, :ask_email, :ask_fio, :return_url, :client_ui_css, 0, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            self::COLUMN_SHOP_CONFIG_ID => $basket->getShopConfigId(),
            self::COLUMN_ACTIVE => $basket->isActive() ? 1 : 0,
            self::COLUMN_NAME => $basket->getName(),
            self::COLUMN_DESCRIPTION => $basket->getDescription(),
            self::COLUMN_ASK_PHONE => $basket->isAskPhone() ? 1 : 0,
            self::COLUMN_ASK_EMAIL => $basket->isAskEmail() ? 1 : 0,
            self::COLUMN_ASK_FIO => $basket->isAskFIO() ? 1 : 0,
            self::COLUMN_RETURN_URL => $basket->getReturnUrl(),
            self::COLUMN_CLIENT_UI_CSS => $basket->getClientUICss(),
        ]);
        $this->logger->info("Basket was saved by id[" . $uuid . "]");
        return $uuid;
    }

    public function getById($basketId) {
        $sql = "select * from $this->tableName where id = :id";
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
        $sql = "select * from $this->tableName where shop_config_id = :shop_config_id";
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
            ->setShopConfig(BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($row[self::COLUMN_SHOP_CONFIG_ID]))
            ->setName($row[self::COLUMN_NAME])
            ->setDescription($row[self::COLUMN_DESCRIPTION])
            ->setCreatedAt($row[self::COLUMN_CREATED_AT])
            ->setActive($row[self::COLUMN_ACTIVE])
            ->setAskFIO($row[self::COLUMN_ASK_FIO])
            ->setAskPhone($row[self::COLUMN_ASK_PHONE])
            ->setAskEmail($row[self::COLUMN_ASK_EMAIL])
            ->setReturnUrl($row[self::COLUMN_RETURN_URL])
            ->setClientUICss($row[self::COLUMN_CLIENT_UI_CSS])
            ->setCheckoutCount($row[self::COLUMN_CHECKOUT_COUNT]);
        return $basket;
    }

    public function incrementCheckoutCount($basketId) {
        $sql = "UPDATE $this->tableName set checkout_count = checkout_count + 1 where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $basketId
        ]);
    }

    public function getByMerchantId($merchantId) {
        $shopConfigsTable = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getTableName();
        $sql = "select b.* from $this->tableName b, $shopConfigsTable sc  where b.shop_config_id = sc.id and sc.merchant_id = :merchant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'merchant_id' => $merchantId,
        ]);
        $baskets = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $baskets[] =  $this->createBasketObject($row);
        }
        return $baskets;
    }

    public function deleteById($productId) {
        $sql = "delete from $this->tableName where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $productId,
        ]);
    }

    public function getByProductId($productId) {
        $basketItemTable = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getTableName();
        $sql = "select b.* from $this->tableName b, $basketItemTable bi  where b.id = bi.basket_id and bi.product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'product_id' => $productId,
        ]);
        $baskets = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $baskets[] =  $this->createBasketObject($row);
        }
        return $baskets;
    }

    public function getTableName() {
        return $this->tableName;
    }
}