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

    /**
     * @param string $id
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface|null
     */
    public function getPlan(string $id)
    {
        foreach ($this->plans as $plan) {
            if ($plan->getId() === $id) {
                return $plan;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasPaidPlans()
    {
        return count($this->getPaidPlans()) > 0;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPlans()
    {
        return $this->plans;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidPlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getPrice() > 0;
        });
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidActivePlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getPrice() > 0 &&
                   $plan->isActive();
        });
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getMonthlyPlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getInterval() === 'monthly';
        });
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidMonthlyPlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getPrice() > 0 &&
                   $plan->getInterval() === 'monthly';
        });
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getYearlyPlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getInterval() === 'yearly';
        });
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    public function getPaidYearlyPlans()
    {
        return $this->filterPlans(function (PlanInterface $plan) {
            return $plan->getPrice() > 0 &&
                   $plan->getInterval() === 'yearly';
        });
    }

    /**
     * @param string $interval
     * @return \WarbleMedia\PhoenixBundle\Billing\PlanInterface[]
     */
    private function filterPlans(callable $filter)
    {
        return array_filter($this->plans, $filter);
    }
}
