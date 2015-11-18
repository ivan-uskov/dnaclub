<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;

class OrdersController extends Controller
{
    /**
     * @Route("/orders", name="ordersList")
     */
    public function ordersListAction(Request $request)
    {
        $orders = $this->getDoctrine()->getRepository('AppBundle:Order')->findAll();
        return $this->render('orders/orders_list.html.twig', ['orders' => $orders]);
    }

    /**
     * @Route("/new-order", name="createOrder")
     */
    public function createOrderAction(Request $request)
    {
        return $this->render('orders/create_order.html.twig');
    }

    /**
     * @Route("/new-order-ajax", name="createOrderAjax")
     */
    public function createOrderAjaxAction(Request $request)
    {
        $post = $request->request;

        $order = new Order();
        $order->setDebt((int)$post->get('debt'));
        $order->setDiscount((int)$post->get('discount'));
        $order->setSum((int)$post->get('paidByCash') + (int)$post->get('paidByReward'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('ordersList');
    }

    /**
     * @Route("/pre-orders", name="preOrdersList")
     */
    public function preOrdersListAction(Request $request)
    {
        return $this->render('orders/pre_orders_list.html.twig');
    }
}