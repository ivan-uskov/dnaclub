<?php

namespace AppBundle\Controller;

use AppBundle\config\SubscriptionType;
use AppBundle\Entity\Subscription;
use AppBundle\Form\MonthSearchForm;
use AppBundle\Form\NewSubscriptionForm;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PaymentController extends Controller
{
    /**
     * @Route("/debtors", name="debtorsList")
     */
    public function debtorsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $debtors = $em->getRepository('AppBundle:Order')->getDebtors();

        return $this->render('payment/debtors_list.html.twig', ['debtors' => $debtors]);
    }

    /**
     * @Route("/rewards", name="rewardsList")
     */
    public function rewardsListAction(Request $request)
    {
        return $this->render('payment/rewards_list.html.twig');
    }

    /**
     * @Route("/subscriptions", name="subscriptionsList")
     */
    public function subscriptionsListAction(Request $request)
    {
        return $this->subscriptionsListImpl($request);
    }

    /**
     * @Route("/clients-subscriptions/{clientId}", name="clientsSubscriptionsList")
     */
    public function clientsSubscriptionsListAction(Request $request, $clientId)
    {
        return $this->subscriptionsListImpl($request, $clientId);
    }

    /**
     * @Route("/subscription/delete/{subscriptionId}", name="deleteSubscription")
     */
    public function deleteSubscriptionAction(Request $request, $subscriptionId)
    {
        return $this->deleteSubscriptionImpl($subscriptionId);
    }

    /**
     * @Route("/clients-subscription/{clientId}/delete/{subscriptionId}", name="deleteClientsSubscription")
     */
    public function deleteClientsSubscriptionAction(Request $request, $clientId, $subscriptionId)
    {
        return $this->deleteSubscriptionImpl($subscriptionId, $clientId);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function subscriptionsListImpl(Request $request, $clientId = null)
    {
        $em = $this->getDoctrine()->getManager();

        $client = null;
        if ($clientId != null)
        {
            $client = $em->getRepository('AppBundle:Client')->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $templateMode = 'clientsSubscriptions';
        }
        else
        {
            $templateMode = 'subscriptions';
        }

        $subscription = new Subscription();
        $subscription->setCount(1);
        $subscription->setDate(new \DateTime());
        $subscriptionForm = new NewSubscriptionForm();
        $subscriptionForm->setClient($client);
        $newSubscriptionForm = $this->createForm($subscriptionForm, $subscription, array('em' => $em));

        $dates = $em->getRepository('AppBundle:MarketingReport')->getMonthsForSelect();
        $searchForm = $this->createForm(new MonthSearchForm(), array('months' => ''), array('dates' => $dates));

        $searchForm->handleRequest($request);

        $newSubscriptionForm->handleRequest($request);
        if ($newSubscriptionForm->isSubmitted() && $newSubscriptionForm->isValid())
        {
            $type = $newSubscriptionForm->getData()->getType();
            $count = $newSubscriptionForm->getData()->getCount();
            $sum = $count * SubscriptionType::getPrice($type);
            $subscription->setSum($sum);
            $em->persist($subscription);
            $em->flush();
        }

        $data = $searchForm->getData();
        $date = $data["months"] ?: key($dates);
        if ($isClientPredefined)
        {
            $subscriptions = $em->getRepository('AppBundle:Subscription')->findByMonthAndClient($date, $client);
        }
        else
        {
            $subscriptions = $em->getRepository('AppBundle:Subscription')->findByMonth($date);
        }

        return $this->render('payment/subscriptions_list.html.twig',
            [
                'searchForm' => $searchForm->createView(),
                'form' => $newSubscriptionForm->createView(),
                'subscriptions' => $subscriptions,
                'mode' => $templateMode,
                'client' => $client
            ]);
    }

    /**
     * @param $subscriptionId
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function deleteSubscriptionImpl($subscriptionId, $clientId = null)
    {
        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AppBundle:Subscription')->find($subscriptionId);

        if ($subscription)
        {
            $subscription->setIsDeleted(true);
            $em->merge($subscription);
            $em->flush();
        }

        if ($clientId == null)
        {
            return $this->redirectToRoute('subscriptionsList');
        }
        else
        {
            return $this->redirectToRoute('clientsSubscriptionsList', ['clientId' => $clientId]);
        }
    }
}