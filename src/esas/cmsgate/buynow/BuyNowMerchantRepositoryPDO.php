<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use PDO;

class BuyNowMerchantRepositoryPDO extends BuyNowMerchantRepository
{
    /**
     * @var PDO
     */
    protected $pdo;
    protected $tableName;

    const COLUMN_ID = 'id';
    const COLUMN_LOGIN = 'login';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_AUTH_HASH = 'auth_hash';


    public function __construct($pdo, $tableName = null)
    {
        parent::__construct();
        $this->pdo = $pdo;
        if ($tableName != null)
            $this->tableName = $tableName;
        else
            $this->tableName = Registry::getRegistry()->getModuleDescriptor()->getCmsAndPaysystemName()
                . '_merchant';
    }

    public function addOrUpdateAuth($login, $password, $hash) {
        $sql = "select * from $this->tableName where login = :login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'login' => $login,
        ]);
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $uuid = $row[self::COLUMN_ID];
            $sql = "UPDATE $this->tableName set password = :password, auth_hash = :auth_hash , last_login_at = CURRENT_TIMESTAMP where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $uuid,
                'password' => BridgeConnector::fromRegistry()->getCryptService()->encrypt($password),
                'auth_hash' => $hash,
            ]);
            return $uuid;
        }
        $uuid = StringUtils::guidv4();
        $sql = "INSERT INTO $this->tableName (id, login, password, auth_hash, created_at, last_login_at) VALUES (:id, :login, :password, :auth_hash, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $uuid,
            'login' => $login,
            'password' => BridgeConnector::fromRegistry()->getCryptService()->encrypt($password),
            'auth_hash' => $hash
        ]);
        return $uuid;
    }

    public function getAuthHashById($id) {
        $sql = "select * from $this->tableName where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        $authHash = null;
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $authHash = $row[self::COLUMN_AUTH_HASH];
        }
        return $authHash;
    }
}