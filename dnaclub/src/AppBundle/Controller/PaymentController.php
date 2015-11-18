<?php

namespace AppBundle\Controller;

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
        return $this->render('payment/subscriptions_list.html.twig');
    }
}