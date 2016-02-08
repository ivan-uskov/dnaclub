<?php

namespace AppBundle\Entity;

use AppBundle\config\PaymentType;

/**
 * OrderPayment
 */
class OrderPayment
{
    /**
     * @var integer
     */
    private $orderPaymentId;

    /**
     * @var integer
     */
    private $paymentType;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $sum;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Reward
     */
    private $reward;


    /**
     * Get orderPaymentId
     *
     * @return integer
     */
    public function getOrderPaymentId()
    {
        return $this->orderPaymentId;
    }

    /**
     * Set paymentType
     *
     * @param integer $paymentType
     *
     * @return OrderPayment
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return integer
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return OrderPayment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set sum
     *
     * @param string $sum
     *
     * @return OrderPayment
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return string
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set order
     *
     * @param Order $order
     *
     * @return OrderPayment
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

    /**
     * Set reward
     *
     * @param Reward $reward
     *
     * @return OrderPayment
     */
    public function setReward(Reward $reward = null)
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Get reward
     *
     * @return Reward
     */
    public function getReward()
    {
        return $this->reward;
    }

    public function isByCash()
    {
        return $this->getPaymentType() == PaymentType::CASH;
    }

    public function getName()
    {
        return PaymentType::getName($this->getPaymentType());
    }
}
