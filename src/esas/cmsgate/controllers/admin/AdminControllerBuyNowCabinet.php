<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use Exception;
use Throwable;

class AdminControllerBuyNowCabinet extends Controller
{
    public function process()
    {
        (new ControllerBridgeCheckAuth())->process(true);
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                Registry::getRegistry()->getConfigForm()->validate();
                Registry::getRegistry()->getConfigForm()->save();
            }
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
        BridgeConnector::fromRegistry()->getAdminConfigPage()->render();
    }
}