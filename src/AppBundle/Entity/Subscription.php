<?php

namespace AppBundle\Entity;
use AppBundle\config\SubscriptionType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscription
 */
class Subscription
{
    /**
     * @var integer
     */
    private $subscriptionId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     * @Assert\GreaterThan(value=0)
     */
    private $count;

    /**
     * @var string
     */
    private $sum;

    /**
     * @var boolean
     */
    private $isDeleted = 0;

    /**
     * @var Client
     */
    private $client;


    /**
     * Get subscriptionId
     *
     * @return integer
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Subscription
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
     * Set type
     *
     * @param integer $type
     *
     * @return Subscription
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeName()
    {
        return SubscriptionType::getName($this->getType());
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return Subscription
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
     * Set sum
     *
     * @param string $sum
     *
     * @return Subscription
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Subscription
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
     * Set client
     *
     * @param Client $client
     *
     * @return Subscription
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
}

