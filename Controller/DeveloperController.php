<?php

namespace WarbleMedia\PhoenixBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

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
            'historicalIndicators'    => $indicators->getHistoricalIndicators(),
            'lastMonthsIndicators'    => $indicators->getHistoricalIndicatorsFor($lastMonth),
            'lastYearsIndicators'     => $indicators->getHistoricalIndicatorsFor($lastYear),
            'subscribersByPlan'       => $indicators->getSubscribersByPlan(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersAction(Request $request)
    {
        $user = $this->getUserOrError();
        $userManager = $this->get('warble_media_phoenix.model.user_manager');

        $query = $request->query->get('query');
        $results = [];

        if (!empty($query)) {
            $results = $userManager->findUsersBySearchString($query, $user);
        }

        return $this->render('WarbleMediaPhoenixBundle:Developer:users.html.twig', [
            'query'   => $query,
            'results' => $results,
        ]);
    }

    /**
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userProfileAction($id)
    {
        // TODO: Implement userProfileAction() method.
    }
}
