<?php

namespace App\Repository;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;
use App\Service\DatabaseService;
use PDO;
use PDOException;
class AddressRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getById(int $id){
        $query = $this->connection->prepare("SELECT * FROM address WHERE id = :id");
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = $this->connection->prepare("SELECT * FROM address");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(AddressEntity $addressEntity){
        try{
        $addressExecution = $this->connection->prepare("INSERT INTO address (state, city, neighborhood, street, number, complement) 
                                                VALUES (:state, :city, :neighborhood, :street, :number, :complement)");
        $addressExecution->execute([
            ':state' => $addressEntity->getUf(),
            ':city' => $addressEntity->getCity(),
            ':neighborhood' => $addressEntity->getNeighborhood(),
            ':street' => $addressEntity->getStreet(),
            ':number' => $addressEntity->getNumber(),
            ':complement' => $addressEntity->getComplement()
        ]);
        $adddressId = $this->connection->lastInsertId();
        return $this->getById($adddressId);
        }catch (PDOException $e){
            return ['error' => $e->getMessage()];
        }
    }

}