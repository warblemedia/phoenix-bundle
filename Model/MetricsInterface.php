<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface MetricsInterface extends \JsonSerializable
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getMonthlyRecurringRevenue();

    /**
     * @param string $monthlyRecurringRevenue
     */
    public function setMonthlyRecurringRevenue(string $monthlyRecurringRevenue);

    /**
     * @return string
     */
    public function getYearlyRecurringRevenue();

    /**
     * @param string $yearlyRecurringRevenue
     */
    public function setYearlyRecurringRevenue(string $yearlyRecurringRevenue);

    /**
     * @return string
     */
    public function getTotalRevenueToDate();

    /**
     * @param string $totalRevenueToDate
     */
    public function setTotalRevenueToDate(string $totalRevenueToDate);

    /**
     * @return int
     */
    public function getNewCustomers();

    /**
     * @param int $newCustomers
     */
    public function setNewCustomers(int $newCustomers);

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt();

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt();
}
