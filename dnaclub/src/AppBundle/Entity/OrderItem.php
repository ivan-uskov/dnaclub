<?php

namespace AppBundle\Entity;

/**
 * OrderItem
 */
class OrderItem
{
    /**
     * @var integer
     */
    private $orderItemId;

    /**
     * @var integer
     */
    private $count;

    /**
     * @var string
     */
    private $cost = 0.00;

    /**
     * @var boolean
     */
    private $state = 0;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var Order
     */
    private $order;


    /**
     * Get orderItemId
     *
     * @return integer
     */
    public function getOrderItemId()
    {
        return $this->orderItemId;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return OrderItem
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set cost
     *
     * @param string $cost
     *
     * @return OrderItem
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return OrderItem
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return OrderItem
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param Order $order
     *
     * @return OrderItem
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}

