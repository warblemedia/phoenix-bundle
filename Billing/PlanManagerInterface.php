<?php

namespace WarbleMedia\PhoenixBundle\Billing;

interface PlanManagerInterface
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
    public function getMonthlyPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidMonthlyPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getYearlyPlans();

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidYearlyPlans();
}
