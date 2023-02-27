<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;

use esas\cmsgate\buynow\BuyNowMerchant;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\SessionUtilsBridge;

abstract class ConfigStorageBuyNow extends ConfigStorageBridge
{
    /**
     * @var BuyNowMerchant
     */
    protected $merchant;

    public function getConfig($key)
    {
        if ($this->merchant == null) {
            $this->merchant = BridgeConnectorBuyNow::fromRegistry()->getBuyNowMerchantRepository()->getById(SessionUtilsBridge::getMerchantUUID());
        }
        if ($key == $this->getConfigFieldLogin())
            return $this->merchant->getLogin();
        elseif ($key == $this->getConfigFieldPassword())
            return $this->merchant->getPassword();
        elseif ($key == RequestParamsBuyNow::SHOP_CONFIG_NAME)
            return BridgeConnectorBuyNow::fromRegistry()->getShopConfigService()->getSessionShopConfigSafe()->getName();
        elseif ($key == RequestParamsBuyNow::SHOP_CONFIG_ACTIVE)
            return BridgeConnectorBuyNow::fromRegistry()->getShopConfigService()->getSessionShopConfigSafe()->isActive();
        else
            return parent::getConfig($key);
    }

    public abstract function getConfigFieldLogin();

    public abstract function getConfigFieldPassword();
}