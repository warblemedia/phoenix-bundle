<?php

namespace WarbleMedia\PhoenixBundle\Templating\Helper;

class MoneyHelper implements MoneyHelperInterface
{
    /** @var string */
    private $defaultCurrencyCode;

    /** @var string */
    private $defaultLocale;

    /**
     * MoneyHelper constructor.
     *
     * @param string $defaultCurrencyCode
     * @param string $defaultLocale
     */
    public function __construct(string $defaultCurrencyCode, string $defaultLocale)
    {
        $this->defaultCurrencyCode = $defaultCurrencyCode;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param float  $amount
     * @param string $currencyCode
     * @param string $locale
     * @return string
     */
    public function formatAmount($amount, $currencyCode = null, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $currencyCode = $currencyCode ?: $this->defaultCurrencyCode;

        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency($amount, $currencyCode);

        if ($formatted === false) {
            $message = 'The amount "%s" of type %s cannot be formatted to currency "%s".';
            throw new \InvalidArgumentException(sprintf($message, $amount, gettype($amount), $currencyCode));
        }

        return $formatted;
    }

    /**
     * @param string $currencyCode
     * @param string $locale
     * @return string
     */
    public function getCurrencySymbol($currencyCode = null, $locale = null)
    {
        $locale = $locale ?: $this->defaultLocale;
        $currencyCode = $currencyCode ?: $this->defaultCurrencyCode;

        $formatter = new \NumberFormatter($locale . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);
        $currencySymbol = $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

        if ($currencySymbol === false) {
            $message = 'The symbol for currency "%s" cannot be determined.';
            throw new \InvalidArgumentException(sprintf($message, $currencyCode));
        }

        return $currencySymbol;
    }
}
