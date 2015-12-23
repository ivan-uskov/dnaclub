<?php

namespace AppBundle\Controller;

use AppBundle\config\SubscriptionType;
use AppBundle\Entity\Subscription;
use AppBundle\Entity\Client;
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

        $types = SubscriptionType::getNames();
        $form = $this->createFormBuilder($subscription)
            ->add('date', 'date', array('label' => 'Дата'))
            ->add('type', 'choice', array('choices' => $types, 'choices_as_values' => false, 'label' => 'Тип подписки'))
            ->add('count', 'number', array('label' => 'Количество'))
            ->add('client', 'entity', array('label' => 'Клиент', 'class' => 'AppBundle:Client','choice_label' => 'fullName', 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.lastName, c.firstName, c.middleName','asc');
            }))
            ->add('save', 'submit', array('label' => 'Добавить подписку'))
            ->getForm();

        $dates = $em->getRepository('AppBundle:MarketingReport')->getMonthsForSelect();
        $searchForm = $this->createFormBuilder()
            ->add('months', 'choice',
                array('choices' => $dates,
                    'choices_as_values' => false
                ))
            ->getForm()
        ;

        $searchForm->handleRequest($request);

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $type = $form->getData()->getType();
            $count = $form->getData()->getCount();
            $sum = $count * SubscriptionType::getPrice($type);
            $subscription->setSum($sum);
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscription);
            $em->flush();
        }
        $data = $searchForm->getData();

        $date = $data["months"] ?: key($dates);
        $subscriptions = $em->getRepository('AppBundle:Subscription')->findByMonth($date);

        return $this->render('payment/subscriptions_list.html.twig', ['searchForm' => $searchForm->createView(), 'form' => $form->createView(), 'subscriptions' => $subscriptions]);
    }
}