<?php

namespace AppBundle\Entity;

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
     * @var boolean
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
     * @var \AppBundle\Entity\Order
     */
    private $order;

    /**
     * @var \AppBundle\Entity\Reward
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
     * @param boolean $paymentType
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
     * @return boolean
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
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderPayment
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set reward
     *
     * @param \AppBundle\Entity\Reward $reward
     *
     * @return OrderPayment
     */
    public function setReward(\AppBundle\Entity\Reward $reward = null)
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Get reward
     *
     * @return \AppBundle\Entity\Reward
     */
    public function getReward()
    {
        return $this->reward;
    }
}

