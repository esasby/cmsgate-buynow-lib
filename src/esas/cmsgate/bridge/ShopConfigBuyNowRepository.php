<?php


namespace esas\cmsgate\bridge;


abstract class ShopConfigBuyNowRepository extends ShopConfigRepository
{
    public abstract function getNewOrderId($shopConfigId);
}