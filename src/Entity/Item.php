<?php namespace App\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
Class Item {

    /** 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer") 
     */
    private $item_id;
    /** @ORM\Column(type="integer") */
    private $quantity;
    /** @ORM\Column(type="decimal") */
    private $unit_price;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="order_id")
     */
    private $order;

    public function getQuantity(){
        return $this->quantity;
    }

    public function getUnitPrice(){
        return $this->unit_price;
    }

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setUnitPrice($unit_price): self
    {
        $this->unit_price = $unit_price;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}