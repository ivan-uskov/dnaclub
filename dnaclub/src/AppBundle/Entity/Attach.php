<?php

namespace AppBundle\Entity;

/**
 * Attach
 */
class Attach
{
    /**
     * @var integer
     */
    private $attachId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $link;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var Client
     */
    private $client;


    /**
     * Get attachId
     *
     * @return integer
     */
    public function getAttachId()
    {
        return $this->attachId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Attach
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Attach
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Attach
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
     * Set client
     *
     * @param Client $client
     *
     * @return Attach
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

