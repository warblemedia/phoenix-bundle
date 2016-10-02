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
}
