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
        $subscriptionRepository = $em->getRepository('AppBundle:Subscription');

        $client = null;
        if ($clientId)
        {
            $client = $em->getRepository('AppBundle:Client')->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        $templateMode = $isClientPredefined ? 'clientsSubscriptions' : 'subscriptions';

        $subscription = new Subscription();
        $subscription->setCount(1);
        $subscription->setDate(new \DateTime());
        $subscription->setClient($client);
        $subscriptionForm = new NewSubscriptionForm();
        $newSubscriptionForm = $this->createForm($subscriptionForm, $subscription, array('em' => $em));

        $dates = $subscriptionRepository->getMonthsForSelect();
        $searchForm = $this->createForm(new MonthSearchForm(), array('months' => ''), array('dates' => $dates));

        $searchForm->handleRequest($request);

        $hideAddForm = true;
        $newSubscriptionForm->handleRequest($request);
        if ($newSubscriptionForm->isSubmitted() && $newSubscriptionForm->isValid())
        {
            $type = $newSubscriptionForm->getData()->getType();
            $count = $newSubscriptionForm->getData()->getCount();
            $sum = $count * SubscriptionType::getPrice($type);
            $subscription->setSum($sum);
            $em->persist($subscription);
            $em->flush();
            if ($isClientPredefined)
            {
                return $this->redirectToRoute('clientsSubscriptionsList', ['clientId' => $clientId]);
            }
            else
            {
                return $this->redirectToRoute('subscriptionsList');
            }
        }
        if ($newSubscriptionForm->isSubmitted() && !$newSubscriptionForm->isValid())
        {
            $hideAddForm = false;
        }

        $data = $searchForm->getData();
        $date = $data["months"] ?: key($dates);

        $subscriptions = $isClientPredefined
            ? $subscriptionRepository->findByClient($client)
            : $subscriptionRepository->findByMonth($date);

        return $this->render('payment/subscriptions_list.html.twig',
            [
                'searchForm' => $searchForm->createView(),
                'form' => $newSubscriptionForm->createView(),
                'subscriptions' => $subscriptions,
                'mode' => $templateMode,
                'client' => $client,
                'hideAddForm' => $hideAddForm
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