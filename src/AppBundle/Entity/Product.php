<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Product
 */
class Product
{
    /**
     * @var integer
     */
    private $productId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     * @Assert\GreaterThan(value=0)
     */
    private $price;

    /**
     * @var string
     */
    private $pieceName;

    /**
     * @var boolean
     */
    private $isDeleted = 0;

    /**
     * @var ProductGroup
     */
    private $productGroup;


    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
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
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set pieceName
     *
     * @param string $pieceName
     *
     * @return Product
     */
    public function setPieceName($pieceName)
    {
        $this->pieceName = $pieceName;

        return $this;
    }

    /**
     * Get pieceName
     *
     * @return string
     */
    public function getPieceName()
    {
        return $this->pieceName;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Product
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
     * Set productGroup
     *
     * @param ProductGroup $productGroup
     *
     * @return Product
     */
    public function setProductGroup(ProductGroup $productGroup = null)
    {
        $this->productGroup = $productGroup;

        return $this;
    }

    /**
     * Get productGroup
     *
     * @return ProductGroup
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }
}

