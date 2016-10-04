<?php

namespace WarbleMedia\PhoenixBundle\Controller;

class DeveloperController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metricsAction()
    {
        $indicators = $this->get('warble_media_phoenix.performance.indicators');

        $lastMonth = new \DateTime();
        $lastMonth->setTime(0, 0, 0)->modify('-1 month');

        $lastYear = new \DateTime();
        $lastYear->setTime(0, 0, 0)->modify('-1 year');

        return $this->render('WarbleMediaPhoenixBundle:Developer:metrics.html.twig', [
            'yearlyRecurringRevenue'  => $indicators->getYearlyRecurringRevenue(),
            'monthlyRecurringRevenue' => $indicators->getMonthlyRecurringRevenue(),
            'totalRevenueToDate'      => $indicators->getTotalRevenueToDate(),
            'trialCustomerCount'      => $indicators->getTrialCustomerCount(),
            'lastMonthsIndicators'    => $indicators->getHistoricalIndicatorsFor($lastMonth),
            'lastYearsIndicators'     => $indicators->getHistoricalIndicatorsFor($lastYear),
        ]);
    }
}
