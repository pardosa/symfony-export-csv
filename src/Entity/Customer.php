<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /** 
     * @ORM\Id()
     * @ORM\Column(type="integer") 
     */
    private $customer_id;
    /** @ORM\Column(length=140) */
    private $first_name;
    /** @ORM\Column(length=140) */
    private $last_name;
    /** @ORM\Column(length=140) */
    private $email;
    /** @ORM\Column(length=140) */
    private $phone;
    
    /**
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="address_id")
     */
    private $shipping_address;

    public function getShippingState() {
        return $this->shipping_address->getState();
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId($id): ?int
    {
        return $this->customer_id = $id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shipping_address;
    }

    public function setShippingAddress(?Address $shipping_address): self
    {
        $this->shipping_address = $shipping_address;

        return $this;
    }
}
