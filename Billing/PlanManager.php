<?php

namespace WarbleMedia\PhoenixBundle\Billing;

class PlanManager implements PlanManagerInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PlanInterface[] */
    protected $plans = [];

    /**
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface $plan
     */
    public function addPlan(PlanInterface $plan)
    {
        $this->plans[] = $plan;
    }
}
