<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity
 * @ORM\Table(name="t_order")
 */
use Doctrine\ORM\Mapping as ORM;

class Order
{
    /** 
     * @ORM\Id()
     * @ORM\Column(type="integer") 
     */
    private $order_id;
    /** @ORM\Column(length=140) */
    private $order_date;
    
    /** @ORM\Column(type="decimal") */
    private $shipping_price;

    /**
     * One order has many items.
     * @ORM\OneToMany(targetEntity="Item", mappedBy="order")
     */
    private $items;
    
    /**
     * One Order has One Customer.
     * @ORM\OneToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
     */
    private $customer;

    /**
     * One order can has many discounts.
     * @ORM\OneToMany(targetEntity="Discount", mappedBy="order")
     * @ORM\OrderBy({"priority" = "ASC", "id" = "ASC"})
     */
    private $discounts;
    
    private $total_unit;
    private $total_order_value;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->discounts = new ArrayCollection();
    }

    public function getOrderId(){
        return $this->order_id;
    }

    public function setOrderId($id){
        return $this->order_id = $id;
    }

    public function getOrderDate(){
        return $this->order_date;
    }

    public function countDiscounts(){
        $totalDiscounts = 0;
        foreach($this->discounts as $discount){
            $totalDiscounts += $discount->addDiscount($this->total_order_value);
        }

        return $totalDiscounts;
    }

    public function getTotalOrderValue(){
        $this->total_unit = 0;
        $this->total_order_value = 0;

        foreach($this->items as $item){
            $this->total_unit += $item->getQuantity();
            $this->total_order_value += $item->getQuantity() * $item->getUnitPrice();
        }
        
        return '$' . number_format($this->total_order_value - $this->countDiscounts(), 2);
    }
    

    public function getTotalOrderWithShipping(){
        $this->orderValue();
        
        return '$' . number_format($this->total_order_value - $this->shipping_price, 2);
    }

    public function getAvarageUnitPrice(){
        //$this->getTotalOrderValue();
        if ($this->total_unit > 0)
            return '$' . number_format($this->total_order_value / $this->total_unit, 2);
        else
            return 0;
    }

    public function countDistinctUnit(){
        return count($this->items);
    }

    public function getTotalUnit(){
        return $this->total_unit;
    }

    public function setOrderDate(string $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDiscounts()
    {
        return $this->discounts;
    }

    public function getShippingPrice()
    {
        return $this->shipping_price;
    }

    public function setShippingPrice($shipping_price): self
    {
        $this->shipping_price = $shipping_price;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function addDiscount(Discount $discount): self
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts[] = $discount;
            $discount->setOrder($this);
        }

        return $this;
    }

    public function removeDiscount(Discount $discount): self
    {
        if ($this->discounts->contains($discount)) {
            $this->discounts->removeElement($discount);
            // set the owning side to null (unless already changed)
            if ($discount->getOrder() === $this) {
                $discount->setOrder(null);
            }
        }

        return $this;
    }

}
