<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use WarbleMedia\PhoenixBundle\Performance\PerformanceIndicators;
use WarbleMedia\PhoenixBundle\Performance\PerformanceIndicatorsInterface;

class MetricsManager implements MetricsManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /** @var \WarbleMedia\PhoenixBundle\Performance\PerformanceIndicatorsInterface */
    private $indicators;

    /** @var string */
    private $metricsClass;

    /**
     * MetricsManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                            $manager
     * @param \WarbleMedia\PhoenixBundle\Performance\PerformanceIndicatorsInterface $indicators
     * @param string                                                                $metricsClass
     */
    public function __construct(ObjectManager $manager, PerformanceIndicatorsInterface $indicators, string $metricsClass)
    {
        $this->manager = $manager;
        $this->indicators = $indicators;
        $this->metricsClass = $metricsClass;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface
     */
    public function createMetrics(): MetricsInterface
    {
        return new $this->metricsClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\MetricsInterface $metrics
     * @param bool                                              $flush
     */
    public function updateMetrics(MetricsInterface $metrics, bool $flush = true)
    {
        $this->manager->persist($metrics);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface
     */
    public function captureTodaysMetrics(): MetricsInterface
    {
        $today = new \DateTime();

        $metrics = $this->createMetrics();
        $metrics->setMonthlyRecurringRevenue($this->indicators->getMonthlyRecurringRevenue());
        $metrics->setYearlyRecurringRevenue($this->indicators->getYearlyRecurringRevenue());
        $metrics->setTotalRevenue($this->indicators->getTotalRevenueForDate($today));
        $metrics->setNewCustomers($this->indicators->getCustomersRegisteredToday());

        return $metrics;
    }
}
