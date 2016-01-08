<?php

namespace AppBundle\Controller;

use AppBundle\lib\OrderItemPeer;
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
        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();
        return $this->render('orders/create_order.html.twig', ['clients' => $clients]);
    }

    /**
     * @Route("/new-order-ajax", name="createOrderAjax")
     */
    public function createOrderAjaxAction(Request $request)
    {
        $post = $request->request;

        $clientId = (int)$post->get('user_name');
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);

        $order = new Order();
        $order->setDebt((int)$post->get('debt'));
        $order->setDiscount((int)$post->get('discount'));
        $order->setSum((int)$post->get('paidByCash') + (int)$post->get('paidByReward'));
        $order->setClient($client);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('ordersList');
    }

    /**
     * @Route("/edit-order/{orderId}", name="editOrder")
     */
    public function editOrderAction(Request $request, $orderId)
    {
        $doctrine   = $this->getDoctrine();
        $orderItems = $doctrine->getRepository("AppBundle:OrderItem")->findBy(['order' => $orderId]);
        $clients    = $doctrine->getRepository('AppBundle:Client')->findAll();
        $order      = $doctrine->getRepository("AppBundle:Order")->find($orderId);

        $params = [
            'order'    => $order,
            'clients'  => $clients,
            'products' => OrderItemPeer::orderItemsToProducts($orderItems)
        ];

        return $this->render('orders/edit_order.html.twig', $params);
    }

    /**
     * @Route("/edit-order-ajax/{orderId}", name="editOrderAjax")
     */
    public function editOrderAjaxAction(Request $request, $orderId)
    {
        $post = $request->request;
        $clientId = (int)$post->get('user_name');

        $doctrine = $this->getDoctrine();
        $client   = $doctrine->getRepository("AppBundle:Client")->find($clientId);
        $order    = $doctrine->getRepository("AppBundle:Order")->find($orderId);

        $order->setDebt((int)$post->get('debt'));
        $order->setDiscount((int)$post->get('discount'));
        $order->setSum((int)$post->get('paidByCash') + (int)$post->get('paidByReward'));
        $order->setClient($client);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('ordersList');
    }

    /**
     * @Route("/delete-order/{orderId}", name="deleteOrder")
     */
    public function deleteOrderAction(Request $request, $orderId)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $order = $doctrine->getRepository("AppBundle:Order")->find($orderId);

        if ($order)
        {
            $em->remove($order);
            $em->flush();
        }

        return $this->redirectToRoute('ordersList');
    }

    /**
     * @Route("/pre-orders", name="preOrdersList")
     */
    public function preOrdersListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $preOrders = $em->getRepository('AppBundle:Order')->getPreOrders();

        return $this->render('orders/pre_orders_list.html.twig', ['pre_orders' => $preOrders]);
    }
}