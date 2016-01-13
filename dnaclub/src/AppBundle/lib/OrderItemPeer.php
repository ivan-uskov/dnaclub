<?php

namespace AppBundle\lib;

use AppBundle\Entity\OrderItem;

class OrderItemPeer
{
    /**
     * @param OrderItem[] $orderItems
     * @return array
     */
    public static function orderItemsToProducts($orderItems)
    {
        $products = [];
        foreach ($orderItems as $orderItem)
        {
            $products[] = $orderItem->getProduct();
        }

        return $products;
    }
}