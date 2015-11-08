<?php

namespace AppBundle\Entity;

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
     * @var \AppBundle\Entity\Client
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
     * @param \AppBundle\Entity\Client $client
     *
     * @return DiseaseHistory
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

