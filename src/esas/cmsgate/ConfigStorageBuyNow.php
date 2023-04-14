<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;


use esas\cmsgate\bridge\Merchant;

abstract class ConfigStorageBuyNow extends ConfigStorageBridge
{
    /**
     * @var Merchant
     */
    protected $merchant;

    public function getConfig($key)
    {
        if ($this->merchant == null) {
            $this->merchant = BridgeConnectorBuyNow::fromRegistry()->getMerchantService()->getMerchantObj();
        }
        if ($key == $this->getConfigFieldLogin())
            return $this->merchant->getLogin();
        elseif ($key == $this->getConfigFieldPassword())
            return $this->merchant->getPassword();
        else
            return parent::getConfig($key);
    }

    public abstract function getConfigFieldLogin();

    public abstract function getConfigFieldPassword();
}