<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\BridgeConnector;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\StringUtils;
use Exception;
use PDO;
use Throwable;

class ShopConfigBuyNowRepositoryPDO extends ShopConfigBuyNowRepository
{

    /**
     * @var PDO
     */
    protected $pdo;
    protected $tableName;
    /**
     * @var Logger
     */
    protected $logger;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_MERCHANT_ID = 'merchant_id';
    const COLUMN_ACTIVE = 'active';
    const COLUMN_ORDER_COUNTER = 'order_counter';
    const COLUMN_CONFIG_DATA = 'config_data';

    public function __construct($pdo, $tableName = null)
    {
        parent::__construct();
        $this->logger = Logger::getLogger(get_class($this));
        $this->pdo = $pdo;
        if ($tableName != null)
            $this->tableName = $tableName;
        else
            $this->tableName = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_shop_config';
    }

    private function createShopConfigObject($row) {
        $shopConfig = new ShopConfigBuyNow();
        try {
            $shopConfig->setConfigArray(json_decode(BridgeConnector::fromRegistry()->getCryptService()->decrypt($row[self::COLUMN_CONFIG_DATA]), true));
        } catch (Throwable $e) {
            $shopConfig->setConfigArray(array()); // new config
        } catch (Exception $e) {
            $shopConfig->setConfigArray(array()); // new config
        }
        $shopConfig->setUuid($row[self::COLUMN_ID]);
        $shopConfig->setName($row[self::COLUMN_NAME]);
        $shopConfig->setMerchantId($row[self::COLUMN_MERCHANT_ID]);
        $shopConfig->setActive($row[self::COLUMN_ACTIVE]);
        $shopConfig->setOrderCounter($row[self::COLUMN_ORDER_COUNTER]);
        return $shopConfig;
    }

    /**
     * @param ShopConfigBuyNow $shopConfig
     * @return mixed|string
     */
    public function saveOrUpdate($shopConfig)
    {
        $configData = json_encode($shopConfig->getConfigArray());
        if (!empty($shopConfig->getUuid())) {
            $sql = "select * from $this->tableName where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $shopConfig->getUuid(),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
                $uuid = $row[self::COLUMN_ID];
                $this->logger->info("Updating shop config for id[" . $uuid . "]");
                $sql = "UPDATE $this->tableName set name = :name, active = :active, config_data = :config_data where id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $shopConfig->getUuid(),
                    self::COLUMN_NAME => $shopConfig->getName(),
                    self::COLUMN_ACTIVE => $shopConfig->isActive() ? 1 : 0,
                    self::COLUMN_CONFIG_DATA => BridgeConnector::fromRegistry()->getCryptService()->encrypt($configData)
                ]);
                return $uuid;
            }
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->tableName (id, name, merchant_id, active, config_data, created_at) VALUES (:id, :name, :merchant_id, :active, :config_data, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            self::COLUMN_NAME => $shopConfig->getName(),
            self::COLUMN_ACTIVE => $shopConfig->isActive() ? 1 : 0,
            self::COLUMN_MERCHANT_ID => $shopConfig->getMerchantId(),
            self::COLUMN_CONFIG_DATA => BridgeConnector::fromRegistry()->getCryptService()->encrypt($configData)
        ]);
        $this->logger->info("Config data was saved by id[" . $uuid . "]");
        return $uuid;
    }


    public function getById($cacheConfigUUID) {
        $sql = "select * from $this->tableName where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $cacheConfigUUID,
        ]);
        $configCache = null;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $configCache =  $this->createShopConfigObject($row);
        }
        return $configCache;
    }

    public function getNewOrderId($shopConfigId) {
        $this->pdo->beginTransaction();
        $this->pdo->prepare("UPDATE $this->tableName set order_counter = order_counter + 1 where id = :id")
            ->execute(['id' => $shopConfigId]);
        $stmt = $this->pdo->prepare("select * from $this->tableName where id = :id");
        $stmt->execute([
            'id' => $shopConfigId,
        ]);
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $newOrderId = $row[self::COLUMN_ORDER_COUNTER];
        }
        $this->pdo->commit();
        return $newOrderId;
    }

    public function saveConfigData($configCacheUUID, $configData) {
        $configData = json_encode($configData);
        $sql = "UPDATE $this->tableName set config_data = :config_data where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $configCacheUUID,
            self::COLUMN_CONFIG_DATA => BridgeConnector::fromRegistry()->getCryptService()->encrypt($configData)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getByMerchantId($merchantId) {
        $sql = "select * from $this->tableName where merchant_id = :merchant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'merchant_id' => $merchantId,
        ]);
        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $products[] =  $this->createShopConfigObject($row);
        }
        return $products;
    }

    public function deleteById($shopConfigId) {
        $sql = "delete from $this->tableName where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $shopConfigId,
        ]);
    }

    public function getTableName() {
        return $this->tableName;
    }
}