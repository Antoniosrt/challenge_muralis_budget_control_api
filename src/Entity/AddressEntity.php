<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
class AddressEntity
{
    private int $id;
    #[Assert\NotBlank]
    private string $uf;
    #[Assert\NotBlank]
    private string $neighborhood;
    #[Assert\NotBlank]
    private string $city;
    #[Assert\NotBlank]
    private string $street;
    #[Assert\NotBlank]
    private string $number;
    private string $complement;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): AddressEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function setUf(string $uf): AddressEntity
    {
        $this->uf = $uf;
        return $this;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(string $neighborhood): AddressEntity
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): AddressEntity
    {
        $this->city = $city;
        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }



    public function setStreet(string $street): AddressEntity
    {
        $this->street = $street;
        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): AddressEntity
    {
        $this->number = $number;
        return $this;
    }

    public function getComplement(): string
    {
        return $this->complement;
    }

    public function setComplement(string $complement): AddressEntity
    {
        $this->complement = $complement;
        return $this;
    }

}