<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\CommonPreset as common;
use esas\cmsgate\utils\htmlbuilder\Page;
use esas\cmsgate\utils\RedirectUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldText;

class AdminBuyNowProductViewPage extends AdminBuyNowPage
{
    /**
     * @var BuyNowProduct
     */
    private $product;

    public function __construct($product) {
        parent::__construct();
        $this->product = $product;
    }


    public function elementPageContent() {
        // TODO: Implement elementPageContent() method.
    }
}