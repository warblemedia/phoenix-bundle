<?php

namespace WarbleMedia\PhoenixBundle\Twig\Extension;

use WarbleMedia\PhoenixBundle\Templating\Helper\MoneyHelperInterface;

class MoneyExtension extends \Twig_Extension
{
    /** @var \WarbleMedia\PhoenixBundle\Templating\Helper\MoneyHelperInterface */
    private $moneyHelper;

    /**
     * MoneyExtension constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Templating\Helper\MoneyHelperInterface $moneyHelper
     */
    public function __construct(MoneyHelperInterface $moneyHelper)
    {
        $this->moneyHelper = $moneyHelper;
    }

    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('currency', [$this->moneyHelper, 'formatAmount'])
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'warble_media_phoenix_money';
    }
}
