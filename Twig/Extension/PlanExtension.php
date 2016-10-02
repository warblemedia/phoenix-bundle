<?php

namespace WarbleMedia\PhoenixBundle\Twig\Extension;

use WarbleMedia\PhoenixBundle\Templating\Helper\PlanHelperInterface;

class PlanExtension extends \Twig_Extension
{
    /** @var \WarbleMedia\PhoenixBundle\Templating\Helper\PlanHelperInterface */
    private $planHelper;

    /**
     * PhoenixExtension constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Templating\Helper\PlanHelperInterface $planHelper
     */
    public function __construct(PlanHelperInterface $planHelper)
    {
        $this->planHelper = $planHelper;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('has_paid_plans', [$this->planHelper, 'hasPaidPlans']),
            new \Twig_SimpleFunction('get_paid_plans', [$this->planHelper, 'getPaidPlans']),
            new \Twig_SimpleFunction('get_paid_monthly_plans', [$this->planHelper, 'getPaidMonthlyPlans']),
            new \Twig_SimpleFunction('get_paid_yearly_plans', [$this->planHelper, 'getPaidYearlyPlans']),
            new \Twig_SimpleFunction('has_monthly_and_yearly_paid_plans', [$this->planHelper, 'hasMonthlyAndYearlyPaidPlans']),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'warble_media_phoenix_plan';
    }
}
