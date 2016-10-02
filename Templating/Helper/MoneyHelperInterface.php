<?php

namespace WarbleMedia\PhoenixBundle\Templating\Helper;

interface MoneyHelperInterface
{
    /**
     * @param float  $amount
     * @param string $currencyCode
     * @param string $locale
     * @return string
     */
    public function formatAmount($amount, $currencyCode = null, $locale = null);
}
