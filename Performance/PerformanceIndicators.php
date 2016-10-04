<?php

namespace WarbleMedia\PhoenixBundle\Performance;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface;
use WarbleMedia\PhoenixBundle\Model\CustomerInterface;
use WarbleMedia\PhoenixBundle\Model\InvoiceInterface;
use WarbleMedia\PhoenixBundle\Model\MetricsInterface;
use WarbleMedia\PhoenixBundle\Model\SubscriptionInterface;

class PerformanceIndicators implements PerformanceIndicatorsInterface
{
    /** @var array */
    private static $revenueCache = [];

    /** @var \Doctrine\ORM\EntityManager */
    private $manager;

    /** @var \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface */
    private $planManager;

    /**
     * PerformanceIndicators constructor.
     *
     * @param \Doctrine\ORM\EntityManager                             $manager
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface $planManager
     */
    public function __construct(EntityManager $manager, PlanManagerInterface $planManager)
    {
        $this->manager = $manager;
        $this->planManager = $planManager;
    }

    /**
     * @return int
     */
    public function getTotalRevenueToDate()
    {
        $invoices = InvoiceInterface::class;

        $dql = 'SELECT sum(i.totalAmount) ' .
               "FROM {$invoices} i";

        $query = $this->manager->createQuery($dql);

        return (int) $query->getSingleScalarResult();
    }

    /**
     * @return string
     */
    public function getYearlyRecurringRevenue()
    {
        return $this->getMonthlyRecurringRevenue() * 12;
    }

    /**
     * @return string
     */
    public function getMonthlyRecurringRevenue()
    {
        return $this->getRecurringRevenueByInterval('monthly') +
               ($this->getRecurringRevenueByInterval('yearly') / 12);
    }

    /**
     * @return int
     */
    public function getTrialCustomerCount()
    {
        $customers = CustomerInterface::class;

        $dql = 'SELECT count(c.id) ' .
               "FROM {$customers} c " .
               'LEFT JOIN c.subscriptions s ' .
               'WHERE c.trialEndsAt > :now ' .
               'GROUP BY c.id ' .
               'HAVING count(s.id) = 0';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('now', new \DateTime());

        try {
            return (int) $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    /**
     * @param \DateTime $date
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface|null
     */
    public function getHistoricalIndicatorsFor(\DateTime $date)
    {
        $metrics = MetricsInterface::class;

        $dql = 'SELECT m ' .
               "FROM {$metrics} m " .
               'WHERE m.createdAt = :date';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('date', $date);

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $interval
     * @return string
     */
    protected function getRecurringRevenueByInterval($interval)
    {
        if (!array_key_exists($interval, self::$revenueCache)) {
            $plans = $interval === 'monthly'
                ? $this->planManager->getMonthlyPlans()
                : $this->planManager->getYearlyPlans();

            self::$revenueCache[$interval] = $this->getRecurringRevenueForPlans($plans);
        }

        return self::$revenueCache[$interval];
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface[] $plans
     * @return string
     */
    protected function getRecurringRevenueForPlans(array $plans)
    {
        $total = '0';

        foreach ($plans as $plan) {
            if ($plan->getPrice() > 0) {
                $count = $this->getPlanSubscriptionsCount($plan);
                $total = bcadd($total, bcmul($plan->getPrice(), $count));
            }
        }

        return $total;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface $plan
     * @return int
     */
    private function getPlanSubscriptionsCount(PlanInterface $plan)
    {
        $subscriptions = SubscriptionInterface::class;

        $dql = 'SELECT count(s.id) ' .
               "FROM {$subscriptions} s " .
               'WHERE s.endsAt IS NULL ' .
               'AND s.stripePlan = :plan_id ' .
               'AND (s.trialEndsAt IS NULL OR s.trialEndsAt <= :now)';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('now', new \DateTime());
        $query->setParameter('plan_id', $plan->getId());

        try {
            return (int) $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }
}
