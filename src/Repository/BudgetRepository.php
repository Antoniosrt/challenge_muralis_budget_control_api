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
    public function getPaginatedExpenses(int $page, int $limit, $initialDate): array
    {

        $offset = ($page - 1) * $limit;

        $query = $this->connection->prepare("
            SELECT * FROM expenses 
            WHERE purchase_date > :initial_date 
            ORDER BY purchase_date DESC 
            LIMIT :limit OFFSET :offset
        ");

        $query->bindValue(':initial_date', $initialDate, PDO::PARAM_STR);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->bindValue(':offset', $offset, PDO::PARAM_INT);
        $query->execute();

        $expenses = $query->fetchAll(PDO::FETCH_ASSOC);

        // Contar total de registros para calcular páginas
        $totalQuery = $this->connection->prepare("SELECT COUNT(*) as total FROM expenses WHERE purchase_date > :initial_date");
        $totalQuery->bindValue(':initial_date', $initialDate, PDO::PARAM_STR);
        $totalQuery->execute();
        $totalRecords = $totalQuery->fetchColumn();
        $totalPages = ceil($totalRecords / $limit);

        return [
            'items' => $expenses,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }
    public function createExpense(BudgetEntity   $budgetEntity): BudgetEntity
    {
        try {

            $expansesExecution = $this->connection->prepare("INSERT INTO expenses (amount, purchase_date, description, payment_type_id, category_id, address_id) 
                                                VALUES (:amount, :purchase_date, :description, :payment_type_id, :category_id, :address_id)");
            $expansesExecution->bindValue(':amount', $budgetEntity->getAmount());
            $expansesExecution->bindValue(':purchase_date', $budgetEntity->getPurchaseDate());
            $expansesExecution->bindValue(':description', $budgetEntity->getDescription());
            $expansesExecution->bindValue(':payment_type_id', $budgetEntity->getPaymentTypeId());
            $expansesExecution->bindValue(':category_id', $budgetEntity->getCategoryId());
            $expansesExecution->bindValue(':address_id', $budgetEntity->getAddressId());
            $expansesExecution->execute();
            $budgetEntity->setId($this->connection->lastInsertId());
            return $budgetEntity;

        } catch (PDOException $e) {
            throw new \PDOException($e->getMessage() . ' ' . $e->getTraceAsString());
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
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getBudgetDataStructure(int $id)
    {
        $query = $this->connection->prepare("
                SELECT 
                    e.id AS expense_id, e.amount, e.purchase_date, e.description,
                    pt.id AS payment_type_id, pt.type,
                    c.id AS category_id, c.name AS category_name, c.description AS category_description,
                    a.id AS address_id, a.state, a.city, a.neighborhood, a.street, a.number, a.complement
                FROM expenses e
                JOIN payment_type pt ON e.payment_type_id = pt.id
                JOIN category c ON e.category_id = c.id
                JOIN address a ON e.address_id = a.id
                WHERE e.id = :id
            ");

        $query->execute([':id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);



        return $row;
    }

    public function getByIdAndTable(int $id, string $table)
    {
        $query = $this->connection->prepare("SELECT * FROM $table WHERE id = :id");
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(PDO::FETCH_ASSOC);
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
    public function validateAndUpdate(array $originalData, array $newData, int $id, string $table)
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
            return ['error' => 'Nenhuma alteração detectada.', 'success' => false];
        }

        // Prepara a consulta de UPDATE com os campos modificados
        $setPart = [];
        foreach ($changes as $field => $newValue) {
            $setPart[] = "$field = :$field";
        }

        $setQuery = implode(', ', $setPart);
        $query = "UPDATE $table SET $setQuery WHERE id = :id";

        // Prepara a execução do comando SQL
        $stmt = $this->connection->prepare($query);

        // Adiciona os parâmetros para o UPDATE
        foreach ($changes as $field => $newValue) {
            $stmt->bindValue(":$field", $newValue);
        }
        $stmt->bindValue(':id', $id);

        try {
            // Executa a consulta
            $stmt->execute();
            // retorna o budget atualizado
            return ['success' => true, 'data' => $this->getByIdAndTable($id,$table)];
        } catch (\Exception $e) {
            // Em caso de erro, retorna uma resposta de erro
            return ['error' => $e->getMessage(),'success' => true,];
        }
    }

    public function deleteBudget(int $id)
    {
        try {
            $query = $this->connection->prepare("DELETE FROM expenses WHERE id = :id");
            $query->execute([':id' => $id]);
            return true;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}