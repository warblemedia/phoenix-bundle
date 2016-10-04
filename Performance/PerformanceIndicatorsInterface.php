<?php

namespace WarbleMedia\PhoenixBundle\Performance;

interface PerformanceIndicatorsInterface
{
    /**
     * @return string
     */
    public function getTotalRevenueToDate();

    /**
     * @return string
     */
    public function getYearlyRecurringRevenue();

    /**
     * @return string
     */
    public function getMonthlyRecurringRevenue();

    /**
     * @return int
     */
    public function getTrialCustomerCount();
}
