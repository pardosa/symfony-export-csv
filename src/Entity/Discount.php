<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

 /**
 * @ORM\MappedSuperclass
 */
 interface DiscountInterface {
    public function addDiscount($price, $value);
 }

class DollarDiscount implements DiscountInterface
{
    public function addDiscount($price, $value)
    {
        return $value;
    }
}

class PercentageDiscount implements DiscountInterface
{

    public function addDiscount($price, $value){
        return $price * $value / 100;
    }
}

class DiscountFactory
{
    /**
     * Count discount by its type.
     *
     * @param $type
     * @return DiscountInterface
     * @throws \Exception
     */
    public static function getDiscountType(string $type): DiscountInterface
    {
        switch ($type) {
            case "DOLLAR":
                return new DollarDiscount;
            case "PERCENTAGE":
                return new PercentageDiscount;
            default:
                throw new \Exception("Unknown Discount Type");
        }
    }
}

 /**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountRepository")
 */
class Discount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="discounts")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="order_id")
     */
    private $order;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
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

    public function addDiscount(DiscountInterface $discount, $price){
        return $discount->addDiscount($price, $this->value);
    }
}
