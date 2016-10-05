<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;

class MetricsManager implements MetricsManagerInterface
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;
    
    /** @var string */
    private $metricsClass;

    /**
     * MetricsManager constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param string                                     $metricsClass
     */
    public function __construct(ObjectManager $manager, string $metricsClass)
    {
        $this->manager = $manager;
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
}
