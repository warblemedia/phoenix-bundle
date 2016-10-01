<?php

namespace WarbleMedia\PhoenixBundle\Billing;

class PlanManager implements PlanManagerInterface
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PlanInterface[] */
    protected $plans = [];

    /**
     * @param string $id
     * @param string $name
     * @param array  $options
     */
    public function addPlan($id, $name, array $options)
    {
        // TODO: Allow some way of using a custom plan implementation
        $this->plans[] = new Plan($id, $name, $options);
    }
}
