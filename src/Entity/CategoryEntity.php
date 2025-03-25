<?php

namespace App\Entity;

class CategoryEntity
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $description;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): CategoryEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): CategoryEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): CategoryEntity
    {
        $this->description = $description;
        return $this;
    }



}