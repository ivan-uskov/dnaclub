<?php

namespace AppBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * DiseaseHistory
 */
class DiseaseHistory
{
    /**
     * @var integer
     */
    private $diseaseHistoryId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $healthState;

    /**
     * @var string
     */
    private $treatment;

    /**
     * @var Client
     */
    private $client;


    /**
     * Get diseaseHistoryId
     *
     * @return integer
     */
    public function getDiseaseHistoryId()
    {
        return $this->diseaseHistoryId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return DiseaseHistory
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
     * Set healthState
     *
     * @param string $healthState
     *
     * @return DiseaseHistory
     */
    public function setHealthState($healthState)
    {
        $this->healthState = $healthState;

        return $this;
    }

    /**
     * Get healthState
     *
     * @return string
     */
    public function getHealthState()
    {
        return $this->healthState;
    }

    /**
     * Set treatment
     *
     * @param string $treatment
     *
     * @return DiseaseHistory
     */
    public function setTreatment($treatment)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * Get treatment
     *
     * @return string
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return DiseaseHistory
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
     * @param ParameterBag $post
     * @param ObjectManager $em
     * @param Client $client
     */
    public function saveFromPost(ParameterBag $post, ObjectManager $em, Client $client, $isNew = true)
	{
		$this->setClient($client);
        if ($isNew)
        {
            $this->setDate(new \DateTime($post->get('date')));
        }
		$this->setHealthState($post->get('health_state'));
		$this->setTreatment($post->get('treatment'));
		$em->persist($this);
		$em->flush();
	}
}

