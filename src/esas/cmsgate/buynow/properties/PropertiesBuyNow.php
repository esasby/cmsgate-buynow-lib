<?php


namespace esas\cmsgate\buynow\properties;


use esas\cmsgate\properties\LocaleProperties;
use esas\cmsgate\properties\PDOConnectionProperties;
use esas\cmsgate\properties\SandboxProperties;
use esas\cmsgate\properties\ViewProperties;
use esas\cmsgate\Registry;

abstract class PropertiesBuyNow implements PDOConnectionProperties, SandboxProperties, ViewProperties, LocaleProperties
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