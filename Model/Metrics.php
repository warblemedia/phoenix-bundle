<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Gedmo\Timestampable\Traits\Timestampable;

abstract class Metrics implements MetricsInterface
{
    use Timestampable;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $monthlyRecurringRevenue;

    /** @var string */
    protected $yearlyRecurringRevenue;

    /** @var string */
    protected $totalRevenueToDate;

    /** @var int */
    protected $newCustomers;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMonthlyRecurringRevenue()
    {
        return $this->monthlyRecurringRevenue;
    }

    /**
     * @param string $monthlyRecurringRevenue
     */
    public function setMonthlyRecurringRevenue(string $monthlyRecurringRevenue)
    {
        $this->monthlyRecurringRevenue = $monthlyRecurringRevenue;
    }

    /**
     * @return string
     */
    public function getYearlyRecurringRevenue()
    {
        return $this->yearlyRecurringRevenue;
    }

    /**
     * @param string $yearlyRecurringRevenue
     */
    public function setYearlyRecurringRevenue(string $yearlyRecurringRevenue)
    {
        $this->yearlyRecurringRevenue = $yearlyRecurringRevenue;
    }

    /**
     * @return string
     */
    public function getTotalRevenueToDate()
    {
        return $this->totalRevenueToDate;
    }

    /**
     * @param string $totalRevenueToDate
     */
    public function setTotalRevenueToDate(string $totalRevenueToDate)
    {
        $this->totalRevenueToDate = $totalRevenueToDate;
    }

    /**
     * @return int
     */
    public function getNewCustomers()
    {
        return $this->newCustomers;
    }

    /**
     * @param int $newCustomers
     */
    public function setNewCustomers(int $newCustomers)
    {
        $this->newCustomers = $newCustomers;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'monthly_recurring_revenue' => $this->monthlyRecurringRevenue,
            'yearly_recurring_revenue'  => $this->yearlyRecurringRevenue,
            'total_revenue_to_date'     => $this->totalRevenueToDate,
            'new_customers'             => $this->newCustomers,
            'created_at'                => $this->createdAt->format('Y-m-d'),
        ];
    }
}
