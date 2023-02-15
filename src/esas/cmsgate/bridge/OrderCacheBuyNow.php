<?php


namespace esas\cmsgate\bridge;


class OrderCacheBuyNow extends OrderCache
{
    private $productId;
    private $orderId; // порядковый номер в рамках shop_config
    private $productsCount;
}