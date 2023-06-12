<?php


namespace esas\cmsgate\buynow\hro\admin;

use esas\cmsgate\buynow\properties\PropertiesBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\pages\PageHRO;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\DisplayErrorPage;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;

abstract class AdminBuyNowPage extends PageHRO implements DisplayErrorPage
{
    public function elementPageHead() {
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
            element::styleFile(dirname(__FILE__) . "/config.css")
        );
    }

    public function getPageTitle() {
        return "BuyNow";
    }

    public function elementPageBody() {
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
                                RedirectServiceBuyNow::fromRegistry()->shopConfigList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_SHOP_CONFIGS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS),
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::fromRegistry()->productList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_PRODUCTS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS),
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::fromRegistry()->basketList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_BASKETS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_BASKETS),
                            bootstrap::elementNavBarListItem(
                                RedirectServiceBuyNow::fromRegistry()->orderList(),
                                Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::MENU_ORDERS),
                                $this->getNavItemId() == RedirectServiceBuyNow::PATH_ADMIN_ORDERS)
                        )
                    ),
                    element::a(
                        attribute::clazz("btn btn-outline-warning my-2 my-sm-0 btn-md"),
                        attribute::href(RedirectServiceBuyNow::fromRegistry()->fromRegistry()->logoutPage()),
                        Translator::fromRegistry()->translate(AdminViewFieldsBuyNow::LOGOUT)
                    )
                )
            ),
            element::main(
                attribute::role("main"),
                attribute::clazz("container"),
                element::br(),
                $this->elementMessageAndContent()
            )
        );
    }

    public function elementMessageAndContent() {
        $messages = $this->elementMessages();
        return ($messages != '' ? $messages . element::br() : "")
            . $this->elementPageContent();
    }

    public abstract function getNavItemId();

    public abstract function elementPageContent();

    public static function elementTestLabel() {
        return
            PropertiesBuyNow::fromRegistry()->isSandbox() ? element::small(
                attribute::style('color: #EC9941!important; vertical-align: sub'),
                'test') : "";
    }

    public function isErrorPage() {
        return Registry::getRegistry()->getMessenger()->hasErrorMessages();
    }
}