<?php

namespace WarbleMedia\PhoenixBundle\Templating\Helper;

interface PlanHelperInterface
{
    /**
     * @return bool
     */
    public function hasPaidPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidMonthlyPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidYearlyPlans();
}
