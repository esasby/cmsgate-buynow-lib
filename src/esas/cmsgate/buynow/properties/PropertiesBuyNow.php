<?php


namespace esas\cmsgate\buynow\properties;


use esas\cmsgate\bridge\properties\PropertiesBridge;
use esas\cmsgate\bridge\properties\RecaptchaProperties;
use esas\cmsgate\properties\LocaleProperties;
use esas\cmsgate\properties\PDOConnectionProperties;
use esas\cmsgate\properties\SandboxProperties;
use esas\cmsgate\properties\ViewProperties;
use esas\cmsgate\Registry;

abstract class PropertiesBuyNow extends PropertiesBridge implements
    PDOConnectionProperties,
    SandboxProperties,
    ViewProperties,
    LocaleProperties,
    RecaptchaProperties
{
    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function fromRegistry()
    {
        return Registry::getRegistry()->getProperties();
    }

    public abstract function getDefaultClientUICssLink();
}