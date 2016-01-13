<?php

namespace AppBundle\Controller;

use AppBundle\lib\OrderItemPeer;
use AppBundle\Entity\OrderItem;
use AppBundle\Form\PreOrderSearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;
use AppBundle\Entity\Product;

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
        $products = [];
        /** @var Product $product */
        foreach ($this->getDoctrine()->getRepository('AppBundle:Product')->findAll() as $product)
        {
            $products[$product->getName()] = [
                'id'    => $product->getProductId(),
                'name'  => $product->getName(),
                'price' => $product->getPrice()
            ];
        }

        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();
        return $this->render('orders/create_order.html.twig', ['clients' => $clients, 'products' => json_encode($products)]);
    }

    /**
     * @Route("/new-order-ajax", name="createOrderAjax")
     */
    public function createOrderAjaxAction(Request $request)
    {
        $post = $request->request;

        $productIds = explode(',', $post->get('product_ids'));
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findBy(['id' => $productIds]);

        $clientId = (int)$post->get('user_name');
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);

        $order = new Order();
        $order->setDebt((int)$post->get('debt'));
        $order->setDiscount((int)$post->get('discount'));
        $order->setSum((int)$post->get('paidByCash') + (int)$post->get('paidByReward'));
        $order->setClient($client);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);

        foreach($products as $product)
        {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($product);

            $em->persist($orderItem);
        }

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

        $searchForm = $this->createForm(new PreOrderSearchForm(), array(
            PreOrderSearchForm::IS_RELEASED_SEARCH_FIELD     => false,
            PreOrderSearchForm::IS_NOT_RELEASED_SEARCH_FIELD => true
        ));

        $searchForm->handleRequest($request);

        $preOrders = $em->getRepository('AppBundle:Order')->getPreOrders($searchForm->getData());

        return $this->render('orders/pre_orders_list.html.twig', ['preOrders' => $preOrders, 'searchForm' => $searchForm->createView()]);
    }

    /**
     * @Route("/pre-order/complete/{orderId}", name="completePreOrder")
     */
    public function completePreOrder(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository("AppBundle:Order")->find($orderId);
        if ($order)
        {
            $order->setActualProductDate(new \DateTime());
            $em->merge($order);
            $em->flush();
        }
        return $this->redirectToRoute('preOrdersList');
    }
}