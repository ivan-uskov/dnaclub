<?php

namespace AppBundle\Controller;

use AppBundle\config\SubscriptionType;
use AppBundle\Entity\Subscription;
use AppBundle\Form\MonthSearchForm;
use AppBundle\Form\NewSubscriptionForm;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * @Route("/debtors", name="debtorsList")
     */
    public function debtorsListAction(Request $request)
    {
        return $this->render('payment/debtors_list.html.twig');
    }

    /**
     * @Route("/rewords", name="rewordsList")
     */
    public function rewordsListAction(Request $request)
    {
        return $this->render('payment/rewords_list.html.twig');
    }

    /**
     * @Route("/subscriptions", name="subscriptionsList")
     */
    public function subscriptionsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $subscription = new Subscription();
        $subscription->setCount(1);
        $subscription->setDate(new \DateTime());
        $newSubscriptionForm = $this->createForm(new NewSubscriptionForm(), $subscription, array('em' => $em));

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
        $subscriptions = $em->getRepository('AppBundle:Subscription')->findByMonth($date);

        return $this->render('payment/subscriptions_list.html.twig', ['searchForm' => $searchForm->createView(), 'form' => $newSubscriptionForm->createView(), 'subscriptions' => $subscriptions]);
    }

    /**
     * @Route("/subscription/delete/{subscriptionId}", name="deleteSubscription")
     */
    public function deleteSubscriptionAction(Request $request, $subscriptionId)
    {
        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AppBundle:Subscription')->find($subscriptionId);

        if ($subscription)
        {
            $subscription->setIsDeleted(true);
            $em->merge($subscription);
            $em->flush();
        }

        return $this->redirectToRoute('subscriptionsList');
    }
}