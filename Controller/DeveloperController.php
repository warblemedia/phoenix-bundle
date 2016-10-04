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

        return $this->render('WarbleMediaPhoenixBundle:Developer:metrics.html.twig', [
            'yearlyRecurringRevenue'  => $indicators->getYearlyRecurringRevenue(),
            'monthlyRecurringRevenue' => $indicators->getMonthlyRecurringRevenue(),
            'totalRevenueToDate'      => $indicators->getTotalRevenueToDate(),
            'trialCustomerCount'      => $indicators->getTrialCustomerCount(),
        ]);
    }
}
