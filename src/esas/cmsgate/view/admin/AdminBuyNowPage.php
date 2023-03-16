<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\pages\PageHRO;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;
use esas\cmsgate\utils\RedirectUtilsBridge;
use esas\cmsgate\view\RedirectServiceBuyNow;

abstract class AdminBuyNowPage extends PageHRO
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
            css::elementLinkCssBootstrapMin(),
            script::elementScriptJquery3Min(),
            script::elementScriptPopper1Min(),
            script::elementScriptBootstrapMin(),
            element::styleFile(dirname(__FILE__) . "/config.css"),
            element::scriptFile(dirname(__FILE__) . "/copyToClipboard.js")
        );
    }

    public function getPageTitle()
    {
        return "BuyNow";
    }

    public function elementPageBody()
    {
        return element::body(
            element::nav(
                attribute::clazz("navbar navbar-expand-md navbar-dark fixed-top bg-dark"),
                element::div(
                    attribute::clazz("container-fluid"),
                    element::a(
                        attribute::clazz("navbar-brand"),
                        attribute::href('#'),
                        element::content(
                            Registry::getRegistry()->getModuleDescriptor()->getModuleFullName() . self::elementTestLabel())
                    ),
                    element::div(
                        attribute::clazz("collapse navbar-collapse"),
                        attribute::id("navbarCollapse"),
                        bootstrap::elementNavBarList(
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::shopConfigList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_SHOP_CONFIGS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS),
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::productList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_PRODUCTS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS),
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::basketList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_BASKETS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_BASKETS)
                        )
                    ),
                    element::a(
                        attribute::clazz("btn btn-outline-warning my-2 my-sm-0 btn-md"),
                        attribute::href(RedirectUtilsBridge::logout()),
                        Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::LOGOUT)
                    )
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