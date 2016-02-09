<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $sum;

    /**
     * @var string
     */
    private $remaining_sum;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $isDeleted = 0;

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
        $this->payments = new ArrayCollection();
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
     * Set remainingSum
     *
     * @param string $remainingSum
     *
     * @return Reward
     */
    public function setRemainingSum($remainingSum)
    {
        $this->remaining_sum = $remainingSum;

        return $this;
    }

    /**
     * Get remainingSum
     *
     * @return string
     */
    public function getRemainingSum()
    {
        return $this->remaining_sum;
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
     * @param OrderPayment $payment
     *
     * @return Reward
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
     * @return Reward
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

    public function saveFromPost(ParameterBag $post, ObjectManager $em, Client $client, $isNew = true)
    {
        $this->setSum($post->get('sum'));
        $this->setDate(new \DateTime($post->get('date')));
        $this->setClient($client);
        if ($isNew)
        {
            $this->setRemainingSum($post->get('sum'));
        }
        $em->persist($this);
        $em->flush();
    }

    public function getName()
    {
        $formatter = new \IntlDateFormatter(\Locale::getDefault(), \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC');
        $formatter->setPattern('LLLL Y');

        return $formatter->format($this->getDate()) . " ({$this->getRemainingSum()}/{$this->getSum()})";
    }
}
