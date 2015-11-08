<?php

namespace AppBundle\Entity;

/**
 * Reward
 */
class Reward
{
    /**
     * @var integer
     */
    private $rewardId;

    /**
     * @var string
     */
    private $sum;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $isDeleted = '0';

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
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get rewardId
     *
     * @return integer
     */
    public function getRewardId()
    {
        return $this->rewardId;
    }

    /**
     * Set sum
     *
     * @param string $sum
     *
     * @return Reward
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reward
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Reward
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Add payment
     *
     * @param \AppBundle\Entity\OrderPayment $payment
     *
     * @return Reward
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
     * @return Reward
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

