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
        $request->getSession()->set('warble_media_phoenix_developer_users.query', $query);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed                                     $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userProfileAction(Request $request, $id)
    {
        $indicators = $this->get('warble_media_phoenix.performance.indicators');
        $planManager = $this->get('warble_media_phoenix.billing.plan_manager');

        $query = $request->getSession()->get('warble_media_phoenix_developer_users.query');
        $profile = $this->getUserProfileOrError($id);
        $customer = $profile->getCustomer();

        $activeSubscription = null;
        if ($customer->hasSubscription()) {
            $activeSubscription = $customer->getSubscription();
        }

        $activePlan = null;
        if ($activeSubscription) {
            $activePlan = $planManager->getPlan($activeSubscription->getStripePlan());
        }

        return $this->render('WarbleMediaPhoenixBundle:Developer:user_profile.html.twig', [
            'query'        => $query,
            'profile'      => $profile,
            'customer'     => $customer,
            'activePlan'   => $activePlan,
            'totalRevenue' => $indicators->getTotalRevenueForCustomer($customer),
        ]);
    }

    /**
     * @param mixed $id
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    protected function getUserProfileOrError($id)
    {
        $userManager = $this->get('warble_media_phoenix.model.user_manager');

        $profile = $userManager->findUserById($id);

        if ($profile === null) {
            throw $this->createNotFoundException(sprintf('User wih id "%s" not found.', $id));
        }

        return $profile;
    }
}
