<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\config\OrderStatus;

/**
 * Order
 * @Table(name="`order`")
 */
class Order
{
    /**
     * @var integer
     */
    private $orderId;

    /**
     * @var integer
     */
    private $status = 0;

    /**
     * @var string
     */
    private $discount = 0.00;

    /**
     * @var string
     */
    private $sum = 0.00;

    /**
     * @var string
     */
    private $debt = 0.00;

    /**
     * @var boolean
     */
    private $isPreOrder = 0;

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
     * @var ArrayCollection
     */
    private $items;

    /**
     * @var ArrayCollection
     */
    private $payments;

    /**
     * @var Client
     */
    private $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
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
     * @param integer $status
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
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return OrderStatus::getName($this->getStatus());
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
     * @param OrderItem $item
     *
     * @return Order
     */
    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param OrderItem $item
     */
    public function removeItem(OrderItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add payment
     *
     * @param OrderPayment $payment
     *
     * @return Order
     */
    public function addPayment(OrderPayment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param OrderPayment $payment
     */
    public function removePayment(OrderPayment $payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * Get payments
     *
     * @return ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return Order
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return Order
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param string $format
     * @return string
     */
    public function getDebtDuration($format = '%a')
    {
        $debtDuration = '';
        if ($this->getDebt() > 0)
        {
            $now = new \DateTime();
            $debtDuration = $now->diff($this->getCreatedAt())->format($format);
        }
        return $debtDuration;
    }

    public function isProductsEditable()
    {
        return $this->getStatus() == OrderStatus::OPEN;
    }

    public function isPaymentEditable()
    {
        return $this->getStatus() == OrderStatus::OPEN;
    }

    public function isNotOpen()
    {
        return $this->getStatus() != OrderStatus::OPEN;
    }

    public function isPaid()
    {
        return $this->getStatus() == OrderStatus::PAID;
    }
}
