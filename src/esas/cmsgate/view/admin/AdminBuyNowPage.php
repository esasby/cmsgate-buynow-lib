<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\controllers\admin\AdminControllerBuyNowBaskets;
use esas\cmsgate\controllers\admin\AdminControllerBuyNowProducts;
use esas\cmsgate\controllers\admin\AdminControllerBuyNowShopConfigs;
use esas\cmsgate\controllers\client\ClientControllerBuyNowBasket;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\CommonPreset as common;
use esas\cmsgate\utils\htmlbuilder\Page;
use esas\cmsgate\utils\RedirectUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldText;

abstract class AdminBuyNowPage extends Page
{

    public function elementPageHead()
    {
        return element::head(
            element::title(
                element::content($this->getPageTitle())
            ),
            $this->elementHeadMetaCharset('utf-8'),
            element::meta(
                attribute::name('viewport'),
                attribute::content('width=device-width, initial-scale=1, shrink-to-fit=no')),
            css::elementLinkCssGoogleFonts("css?family=Merienda+One"),
            css::elementLinkCssGoogleFonts("icon?family=Material+Icons"),
            css::elementLinkCssFontAwesome4Min(),
            css::elementLinkCssBootstrap4Min(),
            script::elementScriptJquery3Min(),
            script::elementScriptPopper1Min(),
            script::elementScriptBootstrap4Min(),
            element::styleFile(dirname(__FILE__) . "/config.css"),
            element::scriptFile(dirname(__FILE__) . "/copyToClipboard.js")
        );
    }

    public function getPageTitle()
    {
        return "Configuration";
    }

    public function elementPageBody()
    {
        return element::body(
            element::nav(
                attribute::clazz("navbar navbar-expand-md navbar-dark fixed-top bg-dark"),
                element::a(
                    attribute::clazz("navbar-brand"),
                    attribute::href('#'),
                    element::content(
                        Registry::getRegistry()->getModuleDescriptor()->getModuleFullName() . self::elementTestLabel())
                ),
                element::div(
                    attribute::clazz("collapse navbar-collapse"),
                    attribute::id("navbarCollapse"),
                    common::elementNavBarList(
                        common::elementNavBarListItem("/configs", "Configurations", $this->getNavItemId() == AdminControllerBuyNowShopConfigs::PATH_ADMIN_CONFIGS),
                        common::elementNavBarListItem("/products", "Products", $this->getNavItemId() == AdminControllerBuyNowProducts::PATH_ADMIN_PRODUCTS),
                        common::elementNavBarListItem("/baskets", "Baskets", $this->getNavItemId() == AdminControllerBuyNowBaskets::PATH_ADMIN_BASKETS)
                    )
                ),
                element::a(
                    attribute::clazz("nav-link btn btn-outline-warning my-2 my-sm-0 btn-sm"),
                    attribute::href(RedirectUtilsBridge::logout()),
                    "Logout"
                )
            ),
            element::main(
                attribute::role("main"),
                attribute::clazz("container"),
                $this->elementPageContent()
            )
        );
    }

    public abstract function getNavItemId();

    public abstract function elementPageContent();

    public static function elementTestLabel() {
        return
            BridgeConnector::fromRegistry()->isSandbox() ? element::small(
                attribute::style('color: #EC9941!important; vertical-align: sub'),
                'test') : "";
    }
}