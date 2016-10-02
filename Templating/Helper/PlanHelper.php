<?php

namespace WarbleMedia\PhoenixBundle\Templating\Helper;

use WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface;

class PlanHelper implements PlanHelperInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface */
    private $planManager;

    /**
     * PlanHelper constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface $planManager
     */
    public function __construct(PlanManagerInterface $planManager)
    {
        $this->planManager = $planManager;
    }

    /**
     * @return bool
     */
    public function hasPaidPlans()
    {
        return $this->planManager->hasPaidPlans();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidPlans()
    {
        return $this->planManager->getPaidPlans();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidMonthlyPlans()
    {
        return $this->planManager->getPaidMonthlyPlans();
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidYearlyPlans()
    {
        return $this->planManager->getPaidYearlyPlans();
    }
}
