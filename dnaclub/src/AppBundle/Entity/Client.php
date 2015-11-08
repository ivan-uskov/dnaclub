<?php

namespace AppBundle\Entity;

/**
 * Client
 */
class Client
{
    /**
     * @var integer
     */
    private $clientId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $city;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $subscriptionDate;

    /**
     * @var boolean
     */
    private $isSchoolLearner = '0';

    /**
     * @var boolean
     */
    private $isOnlineLearner = '0';

    /**
     * @var boolean
     */
    private $isSubscribed = '0';

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var boolean
     */
    private $isDeleted = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $diseaseHistories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $rewards;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diseaseHistories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rewards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Client
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Client
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set subscriptionDate
     *
     * @param \DateTime $subscriptionDate
     *
     * @return Client
     */
    public function setSubscriptionDate($subscriptionDate)
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * Get subscriptionDate
     *
     * @return \DateTime
     */
    public function getSubscriptionDate()
    {
        return $this->subscriptionDate;
    }

    /**
     * Set isSchoolLearner
     *
     * @param boolean $isSchoolLearner
     *
     * @return Client
     */
    public function setIsSchoolLearner($isSchoolLearner)
    {
        $this->isSchoolLearner = $isSchoolLearner;

        return $this;
    }

    /**
     * Get isSchoolLearner
     *
     * @return boolean
     */
    public function getIsSchoolLearner()
    {
        return $this->isSchoolLearner;
    }

    /**
     * Set isOnlineLearner
     *
     * @param boolean $isOnlineLearner
     *
     * @return Client
     */
    public function setIsOnlineLearner($isOnlineLearner)
    {
        $this->isOnlineLearner = $isOnlineLearner;

        return $this;
    }

    /**
     * Get isOnlineLearner
     *
     * @return boolean
     */
    public function getIsOnlineLearner()
    {
        return $this->isOnlineLearner;
    }

    /**
     * Set isSubscribed
     *
     * @param boolean $isSubscribed
     *
     * @return Client
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    /**
     * Get isSubscribed
     *
     * @return boolean
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Client
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
     * @return Client
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Client
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
     * Add note
     *
     * @param \AppBundle\Entity\ClientNote $note
     *
     * @return Client
     */
    public function addNote(\AppBundle\Entity\ClientNote $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \AppBundle\Entity\ClientNote $note
     */
    public function removeNote(\AppBundle\Entity\ClientNote $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add diseaseHistory
     *
     * @param \AppBundle\Entity\DiseaseHistory $diseaseHistory
     *
     * @return Client
     */
    public function addDiseaseHistory(\AppBundle\Entity\DiseaseHistory $diseaseHistory)
    {
        $this->diseaseHistories[] = $diseaseHistory;

        return $this;
    }

    /**
     * Remove diseaseHistory
     *
     * @param \AppBundle\Entity\DiseaseHistory $diseaseHistory
     */
    public function removeDiseaseHistory(\AppBundle\Entity\DiseaseHistory $diseaseHistory)
    {
        $this->diseaseHistories->removeElement($diseaseHistory);
    }

    /**
     * Get diseaseHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiseaseHistories()
    {
        return $this->diseaseHistories;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Client
     */
    public function addOrder(\AppBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\Order $order
     */
    public function removeOrder(\AppBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add reward
     *
     * @param \AppBundle\Entity\Reward $reward
     *
     * @return Client
     */
    public function addReward(\AppBundle\Entity\Reward $reward)
    {
        $this->rewards[] = $reward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param \AppBundle\Entity\Reward $reward
     */
    public function removeReward(\AppBundle\Entity\Reward $reward)
    {
        $this->rewards->removeElement($reward);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * Add subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     *
     * @return Client
     */
    public function addSubscription(\AppBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     */
    public function removeSubscription(\AppBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
}

