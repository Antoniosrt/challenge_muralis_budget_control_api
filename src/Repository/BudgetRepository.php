<?php

namespace App\Repository;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;
use App\Service\DatabaseService;
use PDO;
use PDOException;
class BudgetRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function createExpense(BudgetEntity $budgetEntity,AddressEntity $addressEntity,CategoryEntity $categoryEntity,PaymentTypeEntity $paymentType): bool
    {
        try {
            $this->connection->beginTransaction();
            //Execução da query para salvar o endereço retornando o id para ser salvo na tabela de despesas
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

            // Execução da query para salvar categoria retornando o id para ser salvo  na tabela de despesas
            $categoryExecution = $this->connection->prepare("INSERT INTO category (name,description) 
                                                VALUES (:name,:description)");
            $categoryExecution->execute([
                ':name' => $categoryEntity->getName(),
                ':description' => $categoryEntity->getDescription()
            ]);
            $categoryId = $this->connection->lastInsertId();
            $budgetEntity->setCategoryId($categoryId);
            $budgetEntity->setAddressId($adddressId);

            $expansesExecution = $this->connection->prepare("INSERT INTO expenses (amount, purchase_date, description, payment_type_id, category_id, location_id) 
                                                VALUES (:amount, :purchase_date, :description, :payment_type_id, :category_id, :location_id)");
            $expansesExecution->execute([
                ':amount' => $budgetEntity->getAmount(),
                ':purchase_date' => $budgetEntity->getDataCompra(),
                ':description' => $budgetEntity->getDescription(),
                ':payment_type_id' => $budgetEntity->getPaymentTypeId(),
                ':category_id' => $budgetEntity->getCategoryId(),
                ':address_id' => $budgetEntity->$adddressId()
            ]);

            $this->connection->commit();
            $budgetEntity->setId($this->connection->lastInsertId());
            return $budgetEntity;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function createAddress(AddressEntity $addressEntity): bool
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO address (state, city, neighborhood, street, number, complement) 
                                                VALUES (:state, :city, :neighborhood, :street, :number, :complement)");
            $stmt->execute([
                    ':state' => $addressEntity->getUf(),
                    ':city' => $addressEntity->getCity(),
                    ':neighborhood' => $addressEntity->getNeighborhood(),
                    ':street' => $addressEntity->getStreet(),
                    ':number' => $addressEntity->getNumber(),
                    ':complement' => $addressEntity->getComplement()
                ]);
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        $query = $this->connection->prepare("SELECT * FROM expenses WHERE id = :id");
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch();
    }

    /**
     * Função que valida quais campos foram alterados antes de realizar o UPDATE.
     *
     * @param array $originalData Dados originais da entidade.
     * @param array $newData Dados modificados da entidade.
     * @param string $table Nome da tabela no banco de dados.
     * @param string $primaryKey Nome da chave primária.
     * @param mixed $id O valor da chave primária.
     * @return JsonResponse
     */
    public function validateAndUpdate(array $originalData, array $newData, string $table, string $primaryKey, $id): JsonResponse
    {
        // Detecta as mudanças entre os dados originais e os novos
        $changes = [];
        foreach ($originalData as $field => $originalValue) {
            if (isset($newData[$field]) && $originalValue !== $newData[$field]) {
                $changes[$field] = $newData[$field];
            }
        }

        // Se não houver mudanças, retorna uma resposta informando
        if (empty($changes)) {
            return new JsonResponse(['status' => 'no_changes'], 200);
        }

        // Prepara a consulta de UPDATE com os campos modificados
        $setPart = [];
        foreach ($changes as $field => $newValue) {
            $setPart[] = "$field = :$field";
        }

        $setQuery = implode(', ', $setPart);
        $query = "UPDATE $table SET $setQuery WHERE $primaryKey = :id";

        // Prepara a execução do comando SQL
        $stmt = $this->pdo->prepare($query);

        // Adiciona os parâmetros para o UPDATE
        foreach ($changes as $field => $newValue) {
            $stmt->bindValue(":$field", $newValue);
        }
        $stmt->bindValue(':id', $id);

        try {
            // Executa a consulta
            $stmt->execute();
            return new JsonResponse(['status' => 'success'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, retorna uma resposta de erro
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}