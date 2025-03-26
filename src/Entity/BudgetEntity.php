<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class BudgetEntity
{

    private ?int $id;
    #[Assert\NotBlank]
    private ?string $purchase_date;
    #[Assert\NotBlank]

    private ?int $amount;
    #[Assert\NotBlank]

    private ?string $description;
    #[Assert\NotBlank]

    private ?int $address_id;
    #[Assert\NotBlank]

    private ?int $category_id;
    #[Assert\NotBlank]

    private ?int $payment_type_id;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     * @return void
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getAddressId(): ?int
    {
        return $this->address_id;
    }

    /**
     * @param int|null $address_id
     * @return void
     */
    public function setAddressId(?int $address_id): void
    {
        $this->address_id = $address_id;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    /**
     * @param int|null $category_id
     * @return void
     */
    public function setCategoryId(?int $category_id): void
    {
        $this->category_id = $category_id;
    }


    /**
     * @return int|null
     */
    public function getPaymentTypeId(): ?int
    {
        return $this->payment_type_id;
    }

    /**
     * @param int|null $payment_type_id
     * @return $this
     */
    public function setPaymentTypeId(?int $payment_type_id): BudgetEntity
    {
        $this->payment_type_id = $payment_type_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPurchaseDate(): ?string
    {
        return $this->purchase_date;
    }

    /**
     * @param string|null $purchase_date
     * @return $this
     */
    public function setPurchaseDate(?string $purchase_date): BudgetEntity
    {
        $this->purchase_date = $purchase_date;
        return $this;
    }



}

