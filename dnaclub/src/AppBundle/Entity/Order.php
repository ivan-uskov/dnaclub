<?php

namespace AppBundle\Entity;

/**
 * Order
 */
class Order
{
    /**
     * @var integer
     */
    private $orderId;

    /**
     * @var boolean
     */
    private $status = '0';

    /**
     * @var string
     */
    private $sum = '0.00';

    /**
     * @var string
     */
    private $debt = '0.00';

    /**
     * @var boolean
     */
    private $isPreOrder = '0';

    /**
     * @var \DateTime
     */
    private $plannedProductDate;

    /**
     * @var \DateTime
     */
    private $actualProductDate;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;

    /**
     * @var \AppBundle\Entity\Client
     */
    private $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get orderId
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sum
     *
     * @param string $sum
     *
     * @return Order
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
     * Set debt
     *
     * @param string $debt
     *
     * @return Order
     */
    public function setDebt($debt)
    {
        $this->debt = $debt;

        return $this;
    }

    /**
     * Get debt
     *
     * @return string
     */
    public function getDebt()
    {
        return $this->debt;
    }

    /**
     * Set isPreOrder
     *
     * @param boolean $isPreOrder
     *
     * @return Order
     */
    public function setIsPreOrder($isPreOrder)
    {
        $this->isPreOrder = $isPreOrder;

        return $this;
    }

    /**
     * Get isPreOrder
     *
     * @return boolean
     */
    public function getIsPreOrder()
    {
        return $this->isPreOrder;
    }

    /**
     * Set plannedProductDate
     *
     * @param \DateTime $plannedProductDate
     *
     * @return Order
     */
    public function setPlannedProductDate($plannedProductDate)
    {
        $this->plannedProductDate = $plannedProductDate;

        return $this;
    }

    /**
     * Get plannedProductDate
     *
     * @return \DateTime
     */
    public function getPlannedProductDate()
    {
        return $this->plannedProductDate;
    }

    /**
     * Set actualProductDate
     *
     * @param \DateTime $actualProductDate
     *
     * @return Order
     */
    public function setActualProductDate($actualProductDate)
    {
        $this->actualProductDate = $actualProductDate;

        return $this;
    }

    /**
     * Get actualProductDate
     *
     * @return \DateTime
     */
    public function getActualProductDate()
    {
        return $this->actualProductDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Order
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Order
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\OrderItem $item
     *
     * @return Order
     */
    public function addItem(\AppBundle\Entity\OrderItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\OrderItem $item
     */
    public function removeItem(\AppBundle\Entity\OrderItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add payment
     *
     * @param \AppBundle\Entity\OrderPayment $payment
     *
     * @return Order
     */
    public function addPayment(\AppBundle\Entity\OrderPayment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \AppBundle\Entity\OrderPayment $payment
     */
    public function removePayment(\AppBundle\Entity\OrderPayment $payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Order
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}

