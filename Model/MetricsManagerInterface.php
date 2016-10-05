<?php

namespace WarbleMedia\PhoenixBundle\Model;

interface MetricsManagerInterface
{
    /**
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface
     */
    public function createMetrics(): MetricsInterface;

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\MetricsInterface $metrics
     */
    public function updateMetrics(MetricsInterface $metrics);

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface
     */
    public function captureTodaysMetrics(): MetricsInterface;
}
