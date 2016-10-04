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

    /**
     * @param \DateTime $date
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface|null
     */
    public function getHistoricalIndicatorsFor(\DateTime $date);
}
