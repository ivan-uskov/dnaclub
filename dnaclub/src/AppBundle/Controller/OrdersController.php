<?php

namespace AppBundle\Controller;

use AppBundle\config\OrderStatus;
use AppBundle\config\PaymentType;
use AppBundle\lib\OrderItemPeer;
use AppBundle\Entity\OrderItem;
use AppBundle\Form\OrderSearchForm;
use AppBundle\Form\PreOrderSearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Entity\OrderPayment;
use \Symfony\Component\HttpFoundation\ParameterBag;

class OrdersController extends Controller
{
    /**
     * @Route("/orders", name="ordersList")
     */
    public function ordersListAction(Request $request)
    {
        return $this->ordersListImpl($request);
    }

    /**
     * @Route("/clients-orders/{clientId}", name="clientsOrdersList")
     */
    public function clientsOrdersListAction(Request $request, $clientId)
    {
        return $this->ordersListImpl($request, $clientId);
    }

    /**
     * @Route("/new-order", name="createOrder")
     */
    public function createOrderAction(Request $request)
    {
        $products = $this->prepareProductsList();
        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();
        return $this->render('orders/create_order.html.twig', ['clients' => $clients, 'products' => json_encode($products)]);
    }

    /**
     * @Route("/new-order-ajax", name="createOrderAjax")
     */
    public function createOrderAjaxAction(Request $request)
    {
        $order = new Order();
        $this->saveOrderFromRequest($request->request, $order);
        return $this->redirectToRoute('ordersList');
    }

    /**
     * @Route("/edit-order/{orderId}", name="editOrder")
     */
    public function editOrderAction(Request $request, $orderId)
    {
        $doctrine   = $this->getDoctrine();
        /** @var Order $order */
        $order      = $doctrine->getRepository("AppBundle:Order")->find($orderId);
        $orderItems = $doctrine->getRepository("AppBundle:OrderItem")->findBy(['order' => $orderId]);
        $orderPayment = $this->getOrderPayment($order);
        $products = $this->prepareProductsList();
        $clients    = $doctrine->getRepository('AppBundle:Client')->findAll();

        $params = [
            'order'            => $order,
            'clients'          => $clients,
            'orderPayment'     => $orderPayment,
            'productBlockVars' => [
                'id'         => 'productsSelection',
                'orderItems' => $orderItems,
                'products'   => json_encode($products)
            ]
        ];

        return $this->render('orders/edit_order.html.twig', $params);
    }

    /**
     * @Route("/edit-order-ajax/{orderId}", name="editOrderAjax")
     */
    public function editOrderAjaxAction(Request $request, $orderId)
    {
        /** @var Order $order */
        $order = $this->getDoctrine()->getRepository("AppBundle:Order")->find($orderId);
        $this->saveOrderFromRequest($request->request, $order, false);
        return $this->redirectToRoute('ordersList');
    }

    private function saveOrder(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
    }

    private function saveOrderFromRequest(ParameterBag $post, Order &$order, $isNew = true)
    {
        $clientId = (int)$post->get('user_name');
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);

        $order->setDebt(abs((float)$post->get('debt')));
        $order->setDiscount((float)$post->get('discount'));
        $order->setSum((float)$post->get('cost'));
        $order->setClient($client);

        if (!$isNew)
        {
            $this->deleteOrderItems($order);
        }

        $this->saveOrder($order);
        $this->updateOrderPaidByCash($order, (float)$post->get('paidByCash'));
        $this->fillOrderItems($order, $post);
    }

    private function fillOrderItems(Order $order, ParameterBag $post)
    {
        $em = $this->getDoctrine()->getManager();
        $productsInfo = explode(',', $post->get('products_info', ''));

        foreach($productsInfo as $productInfo)
        {
            $result = explode(':', $productInfo);
            if (count($result) == 2)
            {
                $productId    = (int)$result[0];
                $productCount = (int)$result[1];

                /** @var Product $product */
                $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);

                if ($product)
                {
                    $orderItem = new OrderItem();
                    $orderItem->setOrder($order);
                    $orderItem->setProduct($product);
                    $orderItem->setCount($productCount);
                    $orderItem->setCost($product->getPrice() * $productCount);
                    $em->persist($orderItem);
                }
            }
        }

        $em->flush();
    }

    private function deleteOrderItems(Order $order)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $orderItems = $doctrine->getRepository("AppBundle:OrderItem")->findBy(['order' => $order->getOrderId()]);
        foreach ($orderItems as $orderItem)
        {
            $em->remove($orderItem);
        }

        $em->flush();
    }

    private function prepareProductsList()
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

        return $products;
    }

    private function updateOrderPaidByCash(Order $order, $newValue)
    {
        /** @var OrderPayment $orderPayment */
        $orderPayment = $this->getOrderPayment($order);
        if (!$orderPayment)
        {
            $orderPayment = new OrderPayment();
            $orderPayment->setOrder($order);
            $orderPayment->setPaymentType(PaymentType::CASH);
            $orderPayment->setCreatedAt(new \DateTime());
        }

        $orderPayment->setSum($newValue);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderPayment);
        $em->flush();
    }

    private function getOrderPayment(Order $order)
    {
        $orderPayments = $this->getDoctrine()->getRepository('AppBundle:OrderPayment')->findBy(['order' => $order->getOrderId()]);
        return count($orderPayments) ? $orderPayments[0] : null;
    }

    /**
     * @Route("/delete-order/{orderId}", name="deleteOrder")
     */
    public function deleteOrderAction(Request $request, $orderId)
    {
        return $this->deleteOrderImpl($orderId);
    }

    /**
     * @Route("/delete-clients-order/{clientId}/{orderId}", name="deleteClientsOrder")
     */
    public function deleteClientsOrderAction(Request $request, $clientId, $orderId)
    {
        return $this->deleteOrderImpl($orderId, $clientId);
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

    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function ordersListImpl(Request $request, $clientId = null)
    {
        $client = null;
        if ($clientId != null)
        {
            $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);
        }
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $templateMode = 'clientsOrders';
        }
        else
        {
            $templateMode = 'orders';
        }

        $orderSearchForm = new OrderSearchForm();
        $orderSearchForm->setClient($client);
        $searchForm = $this->createForm($orderSearchForm,
            $orderSearchForm->getInitData(),
            ['em' => $this->getDoctrine()->getManager()]
        );
        $searchForm->handleRequest($request);
        $orders = $this->getDoctrine()->getRepository('AppBundle:Order')->getOrders($searchForm->getData());
        $orderInfo = [];

        /** @var Order $order */
        foreach ($orders as $order)
        {
            $order->setStatus(OrderStatus::getName($order->getStatus()));
            $orderInfo[] = [
                'order' => $order,
                'orderItems' => $this->getDoctrine()->getRepository("AppBundle:OrderItem")->findBy(['order' => $order]),
                'payment' => $this->getOrderPayment($order) ?: new OrderPayment()
            ];
        }

        return $this->render('orders/orders_list.html.twig', [
            'orders' => $orderInfo,
            'searchForm' => $searchForm->createView(),
            'mode' => $templateMode,
            'client' => $client
        ]);
    }

    /**
     * @param $orderId
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function deleteOrderImpl($orderId, $clientId = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $order = $doctrine->getRepository("AppBundle:Order")->find($orderId);
        $orderItems = $doctrine->getRepository("AppBundle:OrderItem")->findBy(['order' => $orderId]);

        foreach ($orderItems as $orderItem)
        {
            $em->remove($orderItem);
        }

        $em->flush();

        if ($order)
        {
            $em->remove($order);
            $em->flush();
        }

        if ($clientId == null)
        {
            return $this->redirectToRoute('ordersList');
        }
        else
        {
            return $this->redirectToRoute('clientsOrdersList', ['clientId', $clientId]);
        }
    }
}