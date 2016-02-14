<?php

namespace AppBundle\Controller;

use AppBundle\config\OrderStatus;
use AppBundle\config\PaymentType;
use AppBundle\lib\OrderItemPeer;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Reward;
use AppBundle\Entity\Repository\RewardRepository;
use AppBundle\Form\OrderSearchForm;
use AppBundle\Form\PreOrderSearchForm;
use AppBundle\utils\ArrayUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $clients = $this->getDoctrine()->getRepository('AppBundle:Client')->getSortedClients();
        $clientId = $request->query->get('clientId');
        return $this->render('orders/create_order.html.twig', ['clients' => $clients, 'currentClientId'  => $clientId, 'products' => json_encode($products)]);
    }

    /**
     * @Route("/new-order-ajax", name="createOrderAjax")
     */
    public function createOrderAjaxAction(Request $request)
    {
        $order = new Order();
        $this->saveOrderFromRequest($request->request, $order);
        return $this->redirectToRoute('editOrder', ['orderId' => $order->getOrderId()]);
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
        $payments = $this->getOrderPayments($order);
        $clients = $doctrine->getRepository('AppBundle:Client')->getSortedClients();
        $rewardsRepository = $this->getDoctrine()->getRepository('AppBundle:Reward'); /** @var $rewardsRepository RewardRepository */
        $rewards = $rewardsRepository->findNotDeletedByClient($order->getClient());

        $params = [
            'order'            => $order,
            'clients'          => $clients,
            'orderPayment'     => $orderPayment,
            'productBlockVars' => [
                'id'         => 'productsSelection',
                'orderItems' => $orderItems,
                'products'   => json_encode($products)
            ],
            'paymentBlockVars' => [
                'clientRewards' => $rewards,
                'id'            => 'paidByCash',
                'payments'      => $payments
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
        return $this->redirectToRoute('editOrder', ['orderId' => $order->getOrderId()]);
    }

    private function saveOrder(Order $order, $isNew)
    {
        $em = $this->getDoctrine()->getManager();
        if ($isNew)
        {
            $em->persist($order);
        }
        else
        {
            $em->merge($order);
        }

        $em->flush();
    }

    private function saveOrderFromRequest(ParameterBag $post, Order &$order, $isNew = true)
    {
        $clientId = (int)$post->get('user_name');
        $client = $this->getDoctrine()->getRepository("AppBundle:Client")->find($clientId);

        $order->setDebt(abs((float)$post->get('debt')));
        $order->setDiscount((float)$post->get('discount'));
        $order->setSum((float)$post->get('cost'));

        if ($post->get('is_pre_order') || $order->getIsPreOrder())
        {
            $order->setIsPreOrder(true);
            $plannedDate = $post->get('planned_product_date') ? new \DateTime($post->get('planned_product_date')) : null;
            $actualDate = $post->get('actual_product_date') ? new \DateTime($post->get('actual_product_date')) : null;
            $order->setPlannedProductDate($plannedDate);
            $order->setActualProductDate($actualDate);
        }

        if (!$isNew)
        {
            if ($order->getStatus() == OrderStatus::OPEN)
            {
                $this->deleteOrderItems($order);
            }
        }
        else
        {
            $order->setClient($client);
            $order->setStatus(OrderStatus::OPEN);
            $order->setCreatedAt(new \DateTime($post->get('orderCreatedDate')));
        }

        if ($order->getStatus() == OrderStatus::OPEN)
        {
            if ($client->getClientId() != $order->getClient()->getClientId())
            {
                $order->setClient($client);
            }
            $date = new \DateTime($post->get('orderCreatedDate'));
            if ($order->getCreatedAt() != $date)
            {
                $order->setCreatedAt($date);
            }
        }

        $this->saveOrder($order, $isNew);
        if ($order->getStatus() == OrderStatus::OPEN)
        {
            $this->fillOrderItems($order, $post);
        }
        $this->updateOrderPaymentInfo($order, $post->get('payment_info'));
        $this->updateOrderPaymentStatus($order);
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
        foreach ($this->getDoctrine()->getRepository('AppBundle:Product')->getActiveProducts() as $product)
        {
            $products[$product->getName()] = [
                'id'    => $product->getProductId(),
                'name'  => $product->getName(),
                'price' => $product->getPrice()
            ];
        }

        return $products;
    }

    private function updateOrderPaymentInfo(Order $order, $paymentInfoJson)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentInfo = ArrayUtils::fromJson($paymentInfoJson);
        $cashPayments = ArrayUtils::getParameter($paymentInfo, 'cash');
        $rewardPayments = ArrayUtils::getParameter($paymentInfo, 'reward');

        if (is_array($cashPayments))
        {
            foreach ($cashPayments as $cashPaymentInfo)
            {
                $sum = (float) $cashPaymentInfo['sum'];
                if ($sum <= 0) continue;

                $orderPayment = new OrderPayment();
                $orderPayment->setOrder($order);
                $orderPayment->setPaymentType(PaymentType::CASH);
                $orderPayment->setCreatedAt(new \DateTime($cashPaymentInfo['date']));
                $orderPayment->setSum($sum);
                $em->persist($orderPayment);
            }
        }

        if (is_array($rewardPayments))
        {
            foreach ($rewardPayments as $rewardPaymentInfo)
            {
                $sum = (float)$rewardPaymentInfo['sum'];
                $rewardId = (int)$rewardPaymentInfo['reward_id'];

                if ($rewardId <= 0) continue;

                $reward = $this->getDoctrine()->getRepository("AppBundle:Reward")->find($rewardId); /** @var Reward $reward */

                if (!$reward) continue;

                $remaining = ((float)$reward->getRemainingSum()) - $sum;
                if ((int)$remaining < 0) continue;
                if ($sum <= 0) continue;

                $reward->setRemainingSum($remaining);
                $em->merge($reward);

                $orderPayment = new OrderPayment();
                $orderPayment->setOrder($order);
                $orderPayment->setPaymentType(PaymentType::REWARD);
                $orderPayment->setCreatedAt(new \DateTime($rewardPaymentInfo['date']));
                $orderPayment->setSum($sum);
                $orderPayment->setReward($reward);
                $em->persist($orderPayment);
            }
        }

        $em->flush();
    }

    private function updateOrderPaymentStatus(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $sum = $this->getOrderPaymentSum($order);
        if ($sum > 0)
        {
            $order->setStatus(OrderStatus::PROCESSING);
            $em->merge($order);
        }

        $fullSum = ($sum + (float)$order->getDiscount());
        if ($fullSum > 0 && $order->getSum() <= $fullSum)
        {
            $order->setStatus(OrderStatus::PAID);
            $em->merge($order);
        }

        $em->flush();
    }

    private function getOrderPayments(Order $order)
    {
        $payments = [];
        $orderPayments = $this->getDoctrine()->getRepository('AppBundle:OrderPayment')->findBy(['order' => $order->getOrderId()]);
        /** @var OrderPayment $payment */
        foreach ($orderPayments as $payment)
        {
            $payments[$payment->getOrderPaymentId()] = $payment;
        }

        return $payments;
    }

    private function getOrderPayment(Order $order)
    {
        $orderPayments = $this->getDoctrine()->getRepository('AppBundle:OrderPayment')->findBy(['order' => $order->getOrderId()]);
        return count($orderPayments) ? $orderPayments[0] : null;
    }

    private function getOrderPaymentSum(Order $order)
    {
        $sum = 0;
        $orderPayments = $this->getDoctrine()->getRepository('AppBundle:OrderPayment')->findBy(['order' => $order->getOrderId()]);
        /** @var OrderPayment $orderPayment */
        foreach ($orderPayments as $orderPayment)
        {
            $sum += (float) $orderPayment->getSum();
        }

        return $sum;
    }

    /**
     * @Route("/delete-order/{orderId}", name="deleteOrder")
     */
    public function deleteOrderAction(Request $request, $orderId)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $order = $doctrine->getRepository("AppBundle:Order")->find($orderId);
        $orderItems = $doctrine->getRepository("AppBundle:OrderItem")->findBy(['order' => $orderId]);
        $orderPayments = $this->getDoctrine()->getRepository('AppBundle:OrderPayment')->findBy(['order' => $orderId]);

        foreach ($orderItems as $orderItem)
        {
            $em->remove($orderItem);
        }

        /**
         * @var OrderPayment $orderPayment
         */
        foreach ($orderPayments as $orderPayment)
        {
            $reward = $orderPayment->getReward();
            $em->remove($orderPayment);
            $em->flush();
            if ($reward)
            {
                $reward->actualizeRemainingSum();
                $em->persist($reward);
            }
        }

        $em->flush();

        if ($order)
        {
            $em->remove($order);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Данные о покупке удалены');
        }

        return new RedirectResponse($request->headers->get('referer'));
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

        $flashBag = $this->get('session')->getFlashBag();
        if ($order)
        {
            try
            {
                $order->setActualProductDate(new \DateTime());
                $em->merge($order);
                $em->flush();
                $flashBag->add('success', 'Предзаказ выдан сегодняшним днем');
            }
            catch(\Exception $e)
            {
                $flashBag->add('error', 'Произошла ошибка!');
            }
        }

        return new RedirectResponse($request->headers->get('referer'));
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

        $orderSearchForm = new OrderSearchForm();
        $searchForm = $this->createForm($orderSearchForm,
            $orderSearchForm->getInitData(),
            ['em' => $this->getDoctrine()->getManager()]
        );

        $orderRepository = $this->getDoctrine()->getRepository('AppBundle:Order');
        $isClientPredefined = (($clientId != null) && ($client != null));
        if ($isClientPredefined)
        {
            $templateMode = 'clientsOrders';
            $orders = $orderRepository->getOrdersByClient($client);
        }
        else
        {
            $templateMode = 'orders';
            $searchForm->handleRequest($request);
            $orders = $orderRepository->getOrders($searchForm->getData());
        }

        $orderInfo = [];

        /** @var Order $order */
        foreach ($orders as $order)
        {
            $order->setStatus(OrderStatus::getName($order->getStatus()));
            $orderInfo[] = [
                'order' => $order,
                'orderItems' => $this->getDoctrine()->getRepository("AppBundle:OrderItem")->findBy(['order' => $order]),
                'payments' => $orderRepository->getPayments($order->getOrderId())
            ];
        }

        return $this->render('orders/orders_list.html.twig', [
            'orders' => $orderInfo,
            'searchForm' => $searchForm->createView(),
            'mode' => $templateMode,
            'client' => $client
        ]);
    }
}