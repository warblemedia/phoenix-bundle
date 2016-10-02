<?php

namespace WarbleMedia\PhoenixBundle\Billing;

interface PlanManagerInterface
{
    /**
     * @param string $id
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface|null
     */
    public function getPlan(string $id);

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
    public function getPaidActivePlans();

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
