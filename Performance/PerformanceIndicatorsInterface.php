<?php

namespace WarbleMedia\PhoenixBundle\Performance;

use WarbleMedia\PhoenixBundle\Model\CustomerInterface;

interface PerformanceIndicatorsInterface
{
    /**
     * @return string
     */
    public function getTotalRevenueToDate();

    /**
     * @return string
     */
    public function getTotalRevenueForToday();

    /**
     * @param \DateTime $date
     * @return string
     */
    public function getTotalRevenueForDate(\DateTime $date);

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @return string
     */
    public function getTotalRevenueForCustomer(CustomerInterface $customer);

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
     * @return int
     */
    public function getCustomersRegisteredToday();

    /**
     * @param \DateTime $date
     * @return int
     */
    public function getCustomersRegisteredOnDate(\DateTime $date);

    /**
     * @param int $count
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface[]
     */
    public function getHistoricalIndicators(int $count = 60);

    /**
     * @param \DateTime $date
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface|null
     */
    public function getHistoricalIndicatorsFor(\DateTime $date);

    /**
     * @return array
     */
    public function getSubscribersByPlan();
}
