<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\lang;

use esas\cmsgate\properties\PropertiesBuyNow;

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