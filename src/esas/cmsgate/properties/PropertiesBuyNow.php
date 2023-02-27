<?php


namespace esas\cmsgate\properties;


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
}