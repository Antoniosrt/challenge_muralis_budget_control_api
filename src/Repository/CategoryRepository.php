<?php

namespace App\Repository;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;
use App\Service\DatabaseService;
use PDO;
use PDOException;
class CategoryRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getById(int $id){
        $query = $this->connection->prepare("SELECT * FROM category WHERE id = :id");
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = $this->connection->prepare("SELECT * FROM category");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(CategoryEntity $data)
    {
        $query = $this->connection->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
        $query->execute([
            ':name' => $data->getName(),
            ':description' => $data->getDescription()
        ]);

        return $this->getById($this->connection->lastInsertId());
    }

}