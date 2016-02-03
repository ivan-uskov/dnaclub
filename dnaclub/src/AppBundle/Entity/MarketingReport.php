<?php

namespace AppBundle\Entity;

use AppBundle\config\SubscriptionType;

class MarketingReport
{

    /**
     * @var integer
     */
    private $marketingReportId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $startSum = 0;

    /**
     * @var string
     */
    private $orderSum = 0;

    /**
     * @var integer
     */
    private $contract = 0;

    /**
     * @var string
     */
    private $contractSum = 0;

    /**
     * @var integer
     */
    private $maintenance = 0;

    /**
     * @var string
     */
    private $maintenanceSum = 0;

    /**
     * @var Client
     */
    private $client;


    /**
     * Get marketingReportId
     *
     * @return integer
     */
    public function getMarketingReportId()
    {
        return $this->marketingReportId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MarketingReport
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
     * Set startSum
     *
     * @param string $startSum
     *
     * @return MarketingReport
     */
    public function setStartSum($startSum)
    {
        $this->startSum = $startSum;

        return $this;
    }

    /**
     * Get startSum
     *
     * @return string
     */
    public function getStartSum()
    {
        return $this->startSum;
    }

    /**
     * Set orderSum
     *
     * @param string $orderSum
     *
     * @return MarketingReport
     */
    public function setOrderSum($orderSum)
    {
        $this->orderSum = $orderSum;

        return $this;
    }

    /**
     * Get orderSum
     *
     * @return string
     */
    public function getOrderSum()
    {
        return $this->orderSum;
    }

    /**
     * Set contract
     *
     * @param integer $contract
     *
     * @return MarketingReport
     */
    public function setContract($contract)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return integer
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set contractSum
     *
     * @param string $contractSum
     *
     * @return MarketingReport
     */
    public function setContractSum($contractSum)
    {
        $this->contractSum = $contractSum;

        return $this;
    }

    /**
     * Get contractSum
     *
     * @return string
     */
    public function getContractSum()
    {
        return $this->contractSum;
    }

    /**
     * Set maintenance
     *
     * @param integer $maintenance
     *
     * @return MarketingReport
     */
    public function setMaintenance($maintenance)
    {
        $this->maintenance = $maintenance;

        return $this;
    }

    /**
     * Get maintenance
     *
     * @return integer
     */
    public function getMaintenance()
    {
        return $this->maintenance;
    }

    /**
     * Set maintenanceSum
     *
     * @param string $maintenanceSum
     *
     * @return MarketingReport
     */
    public function setMaintenanceSum($maintenanceSum)
    {
        $this->maintenanceSum = $maintenanceSum;

        return $this;
    }

    /**
     * Get maintenanceSum
     *
     * @return string
     */
    public function getMaintenanceSum()
    {
        return $this->maintenanceSum;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return MarketingReport
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

    public function getSubscriptionsInfo()
    {
        return SubscriptionType::formatSubscriptionInfo($this->getContract(), $this->getMaintenance());
    }
}
