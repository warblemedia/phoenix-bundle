<?php

namespace WarbleMedia\PhoenixBundle\Performance;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use WarbleMedia\PhoenixBundle\Billing\PlanInterface;
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
    public function getTotalRevenueForToday()
    {
        return $this->getTotalRevenueForDate(new \DateTime());
    }

    /**
     * @param \DateTime $date
     * @return string
     */
    public function getTotalRevenueForDate(\DateTime $date)
    {
        $invoices = InvoiceInterface::class;

        $dql = 'SELECT sum(i.totalAmount) ' .
               "FROM {$invoices} i " .
               'WHERE i.createdAt LIKE :date';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('date', $date->format('Y-m-d') . '%');

        return (int) $query->getSingleScalarResult();
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\CustomerInterface $customer
     * @return string
     */
    public function getTotalRevenueForCustomer(CustomerInterface $customer)
    {
        $invoices = InvoiceInterface::class;

        $dql = 'SELECT sum(i.totalAmount) ' .
               "FROM {$invoices} i " .
               'WHERE i.customer = :customer';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('customer', $customer);

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
     * @return int
     */
    public function getCustomersRegisteredToday()
    {
        return $this->getCustomersRegisteredOnDate(new \DateTime());
    }

    /**
     * @param \DateTime $date
     * @return int
     */
    public function getCustomersRegisteredOnDate(\DateTime $date)
    {
        $customers = CustomerInterface::class;

        $dql = 'SELECT count(c.id) ' .
               "FROM {$customers} c " .
               'WHERE c.createdAt LIKE :date';

        $query = $this->manager->createQuery($dql);
        $query->setParameter('date', $date->format('Y-m-d') . '%');

        try {
            return (int) $query->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    /**
     * @param int $count
     * @return \WarbleMedia\PhoenixBundle\Model\MetricsInterface[]
     */
    public function getHistoricalIndicators(int $count = 60)
    {
        $metrics = MetricsInterface::class;

        $dql = 'SELECT m ' .
               "FROM {$metrics} m " .
               'ORDER BY m.createdAt DESC';

        $query = $this->manager->createQuery($dql);
        $query->setMaxResults($count);

        return array_reverse($query->getResult());
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
     * @return array
     */
    public function getSubscribersByPlan()
    {
        $plans = [];

        foreach ($this->planManager->getPlans() as $plan) {
            $plans[] = [
                'name'     => $plan->getName(),
                'interval' => $plan->getInterval(),
                'count'    => $this->getPlanSubscriptionsCount($plan),
                'trialing' => $this->getPlanTrialsCount($plan),
            ];
        }

        usort($plans, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $plans;
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
    protected function getPlanSubscriptionsCount(PlanInterface $plan)
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

    /**
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanInterface $plan
     * @return int
     */
    protected function getPlanTrialsCount(PlanInterface $plan)
    {
        $subscriptions = SubscriptionInterface::class;

        $dql = 'SELECT count(s.id) ' .
               "FROM {$subscriptions} s " .
               'WHERE s.endsAt IS NULL ' .
               'AND s.stripePlan = :plan_id ' .
               'AND s.trialEndsAt > :now';

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
