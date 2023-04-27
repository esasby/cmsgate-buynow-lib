<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\buynow\lang;

use esas\cmsgate\buynow\properties\PropertiesBuyNow;
use esas\cmsgate\lang\LocaleLoaderCms;

class LocaleLoaderBuyNow extends LocaleLoaderCms
{
    public function __construct()
    {
        $this->addExtraVocabularyDir(dirname(__FILE__));
    }

    public function getLocale() {
        return PropertiesBuyNow::fromRegistry()->getLocale();
    }
}