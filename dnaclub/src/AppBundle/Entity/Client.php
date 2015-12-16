<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\ParameterBag;

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
    private $isSchoolLearner = 0;

    /**
     * @var boolean
     */
    private $isOnlineLearner = 0;

    /**
     * @var boolean
     */
    private $isSubscribed = 0;

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
    private $isDeleted = 0;

    /**
     * @var ArrayCollection
     */
    private $notes;

    /**
     * @var ArrayCollection
     */
    private $diseaseHistories;

    /**
     * @var ArrayCollection
     */
    private $orders;

    /**
     * @var ArrayCollection
     */
    private $rewards;

    /**
     * @var ArrayCollection
     */
    private $subscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->diseaseHistories = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->rewards = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
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
     * @param ClientNote $note
     *
     * @return Client
     */
    public function addNote(ClientNote $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param ClientNote $note
     */
    public function removeNote(ClientNote $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return ArrayCollection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add diseaseHistory
     *
     * @param DiseaseHistory $diseaseHistory
     *
     * @return Client
     */
    public function addDiseaseHistory(DiseaseHistory $diseaseHistory)
    {
        $this->diseaseHistories[] = $diseaseHistory;

        return $this;
    }

    /**
     * Remove diseaseHistory
     *
     * @param DiseaseHistory $diseaseHistory
     */
    public function removeDiseaseHistory(DiseaseHistory $diseaseHistory)
    {
        $this->diseaseHistories->removeElement($diseaseHistory);
    }

    /**
     * Get diseaseHistories
     *
     * @return ArrayCollection
     */
    public function getDiseaseHistories()
    {
        return $this->diseaseHistories;
    }

    /**
     * Add order
     *
     * @param Order $order
     *
     * @return Client
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param Order $order
     */
    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

	public function getLastOrder()
	{
		$sortCriteria = Criteria::create()->orderBy(['createdAt' => Criteria::DESC]);
		return $this->getOrders()->matching($sortCriteria)->first();
	}

	public function getLastOrderDateStr()
	{
		$lastOrder = $this->getLastOrder();
		return $lastOrder ? $lastOrder->getCreatedAt()->format("Y-m-d H:i:s") : "";
	}

    /**
     * Add reward
     *
     * @param Reward $reward
     *
     * @return Client
     */
    public function addReward(Reward $reward)
    {
        $this->rewards[] = $reward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param Reward $reward
     */
    public function removeReward(Reward $reward)
    {
        $this->rewards->removeElement($reward);
    }

    /**
     * Get rewards
     *
     * @return ArrayCollection
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * Add subscription
     *
     * @param Subscription $subscription
     *
     * @return Client
     */
    public function addSubscription(Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param Subscription $subscription
     */
    public function removeSubscription(Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

	public function saveFromPost(ParameterBag $post, ObjectManager $em)
	{
		$this->setLastName($post->get('last_name'));
		$this->setFirstName($post->get('first_name'));
		$this->setMiddleName($post->get('middle_name'));
		$this->setBirthday(new \DateTime($post->get('birthday')));
		$this->setCity($post->get('city'));
		$this->setIsSubscribed(($post->get('is_subscribed') === 'on') ? 1 : 0);
		$this->setIsSchoolLearner(($post->get('is_school_learner') === 'on') ? 1 : 0);
		$this->setIsOnlineLearner(($post->get('is_online_learner') === 'on') ? 1 : 0);
		$this->setPhone($post->get('phone'));
		$this->setEmail($post->get('email'));
		$this->setSubscriptionDate($post->get('subscriptionDate'));

		$oldNotes = $this->getNotes();
		$itNotes = $oldNotes->getIterator();
		foreach ($itNotes as $note)
		{
			$em->remove($note);
		}
		$em->flush();
		$noteStr = $post->get('notes');
		if ($noteStr !== '')
		{
			$note = new ClientNote();
			$note->setText($noteStr);
			$note->setClient($this);
			$em->persist($note);
			$this->addNote($note);
		}

		$em->persist($this);
		$em->flush();
	}
}

