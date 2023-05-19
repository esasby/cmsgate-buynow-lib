<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 18.02.2020
 * Time: 11:11
 */

namespace esas\cmsgate\buynow\messenger;

use esas\cmsgate\messenger\Messages;

class MessagesBuyNow extends Messages
{
    const BASKET_IS_INACTIVE = 'basket_is_inactive';
    const BASKET_INCORRECT_ID = 'basket_incorrect_id';
    const BASKET_IS_EXPIRED = 'basket_is_expired';
    const BASKET_LIMIT_REACHED = 'basket_limit_reached';
}