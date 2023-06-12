<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\service\HooksBuyNow;
use esas\cmsgate\hro\pages\ClientOrderCompletionPageHROFactory;
use esas\cmsgate\Registry;
use Exception;
use Throwable;

class ClientControllerBuyNowOrder extends ClientControllerBuyNow
{
    protected $orderId;

    /**
     * ClientControllerBuyNowOrder constructor.
     * @param $orderId
     */
    public function __construct($orderId) {
        parent::__construct();
        $this->orderId = $orderId;
    }

    public function process() {
        $completionPageBuilder = ClientOrderCompletionPageHROFactory::findBuilder();
        try {
            OrderService::fromRegistry()->loadSessionOrderById($this->orderId);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            $completionPageBuilder->setOrderWrapper($orderWrapper);
            $basket = BasketBuyNowRepository::fromRegistry()->getById(SessionServiceBridge::fromRegistry()->getOrderObj()->getBasketId());
            $completionPageBuilder->addCssLink($this->getClientUICssLink($basket));
            HooksBuyNow::fromRegistry()->onOrderDisplay($orderWrapper, $completionPageBuilder);
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } finally {
            $completionPageBuilder->render();
        }
    }


}