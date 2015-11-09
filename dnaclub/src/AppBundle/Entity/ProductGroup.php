<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProductGroup
 */
class ProductGroup
{
    /**
     * @var integer
     */
    private $productGroupId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get productGroupId
     *
     * @return integer
     */
    public function getProductGroupId()
    {
        return $this->productGroupId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductGroup
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
     * Add product
     *
     * @param Product $product
     *
     * @return ProductGroup
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }
}

