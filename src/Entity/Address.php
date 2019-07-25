<?php namespace App\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */

Class Address {

    /** 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer") 
     */
    private $address_id;
    /** @ORM\Column(length=140) */
    private $street;
    /** @ORM\Column(length=140) */
    private $postcode;
    /** @ORM\Column(length=140) */
    private $suburb;
    /** @ORM\Column(length=140) */
    private $state;

    public function getState(){
        return $this->state;
    }

    public function getAddressId(): ?int
    {
        return $this->address_id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getSuburb(): ?string
    {
        return $this->suburb;
    }

    public function setSuburb(string $suburb): self
    {
        $this->suburb = $suburb;

        return $this;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }
}