<?php


namespace esas\cmsgate\buynow\hro\client;


use esas\cmsgate\hro\carousels\CarouselHROFactory;
use esas\cmsgate\hro\carousels\CarouselItemHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class ClientBuyNowHomeHRO_v1 extends ClientBuyNowPage implements ClientBuyNowHomeHRO
{
    protected $pageHeaderText;
    protected $pageHeaderDetailsText;
    protected $bodyText;
    protected $screenShotsArray;

    /**
     * @param mixed $pageHeaderText
     * @return ClientBuyNowHomeHRO_v1
     */
    public function setPageHeaderText($pageHeaderText) {
        $this->pageHeaderText = $pageHeaderText;
        return $this;
    }

    /**
     * @param mixed $pageHeaderDetailsText
     * @return ClientBuyNowHomeHRO_v1
     */
    public function setPageHeaderDetailsText($pageHeaderDetailsText) {
        $this->pageHeaderDetailsText = $pageHeaderDetailsText;
        return $this;
    }

    /**
     * @param mixed $bodyText
     * @return ClientBuyNowHomeHRO_v1
     */
    public function setBodyText($bodyText) {
        $this->bodyText = $bodyText;
        return $this;
    }

    /**
     * @param $screenShot mixed
     * @return ClientBuyNowHomeHRO_v1
     */
    public function addScreenShot($screenShot) {
        $this->screenShotsArray[] = $screenShot;
        return $this;
    }

    public static function builder() {
        return new ClientBuyNowHomeHRO_v1();
    }

    public function getElementSectionHeaderTitle() {
        return $this->pageHeaderText;
    }

    public function getElementSectionHeaderDetails() {
        return $this->pageHeaderDetailsText;
    }

    public function elementPageContent() {
        return
            element::content($this->bodyText)
            . element::div(
                attribute::clazz("container bg-light p-3 border rounded"),
                $this->elementScreenCarousel())
            . element::br();
    }

    public function elementPageErrorContent() {
        return $this->elementPageContent();
    }

    public function elementScreenCarousel() {
        $itemExtraClass = "bg-light p-5";
        $carousel = CarouselHROFactory::findBuilder()
            ->setId("buyNowScreensCarousel")
            ->showIndicators()
            ->showDark();
        foreach ($this->screenShotsArray as $screenShot) {
            $carousel
                ->addItem(
                    CarouselItemHROFactory::findBuilder()
                        ->setImage($screenShot)
                        ->setExtClass($itemExtraClass)
                        ->setActive()
                        ->build());
        }
        return $carousel->build();
    }
}