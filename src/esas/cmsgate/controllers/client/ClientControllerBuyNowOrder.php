<?php


namespace esas\cmsgate\controllers\client;


use esas\cmsgate\bridge\OrderDataBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPanel;
use esas\cmsgate\epos\controllers\ControllerEposInvoiceAdd;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\SessionUtilsBridge;
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
        try {
//            $order = BridgeConnectorBuyNow::fromRegistry()->getOrderCacheRepository()->getByUUID($this->orderId);
            $completionPageBuilder = HROFactoryCmsGate::fromRegistry()->createClientOrderCompletionPage();
            BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService()->loadSessionOrderCacheById($this->orderId);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            $completionPageBuilder->setOrderWrapper($orderWrapper);
            /** @var OrderDataBuyNow $orderData */
            $orderData = SessionUtilsBridge::getOrderCacheObj()->getOrderData();
            $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($orderData->getBasketId());
            $completionPageBuilder->addCssLink($this->getClientUICssLink($basket));
            if ($orderWrapper->getExtId() == null || $orderWrapper->getExtId() == '') {
                $controller = new ControllerEposInvoiceAdd();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerEposCompletionPanel();
            $completionPanel = $controller->process($orderWrapper);
            $completionPageBuilder->setElementCompletionPanel($completionPanel->build());
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } finally {
            $completionPageBuilder->render();
        }
    }
}