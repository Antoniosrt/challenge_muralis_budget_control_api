<?php

namespace App\Repository;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;
use App\Service\DatabaseService;
use PDO;
use PDOException;
class PaymentTypeRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getById(int $id){
        $query = $this->connection->prepare("SELECT * FROM payment_type WHERE id = :id");
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = $this->connection->prepare("SELECT * FROM payment_type");
        $query->execute();
        return $query->fetchAll();
    }

}