<?php

use PHPUnit\Framework\TestCase;
use App\Repository\BudgetRepository;
use App\Entity\BudgetEntity;
use App\Entity\AddressEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;

class BudgetRepositoryTest extends TestCase
{
    private $pdoMock;
    private $budgetRepository;

    protected function setUp(): void
    {
        // Criar um mock do PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Criar uma instância do repositório, passando o mock do PDO
        $this->budgetRepository = new BudgetRepository($this->pdoMock);
    }

    public function testCreateExpense()
    {
        // Criar uma instância simulada de BudgetEntity
        $budgetEntity = $this->createMock(BudgetEntity::class);
        $budgetEntity->method('getAmount')->willReturn(100);
        $budgetEntity->method('getPurchaseDate')->willReturn('2025-03-25 10:00:00');
        $budgetEntity->method('getDescription')->willReturn('Compra de materiais');
        $budgetEntity->method('getPaymentTypeId')->willReturn(1);
        $budgetEntity->method('getCategoryId')->willReturn(1);
        $budgetEntity->method('getAddressId')->willReturn(1);
        $pdoStatementMock = $this->createMock(PDOStatement::class);

        $pdoStatementMock->method('execute')->willReturn(true);
        $pdoStatementMock->method('fetchAll')->willReturn([]);
        $pdoStatementMock->method('fetchColumn')->willReturn("1");  // Retorno como string

        $this->pdoMock->method('prepare')->willReturn($pdoStatementMock);
        $this->pdoMock->method('lastInsertId')->willReturn("1");  // Retorno como string

        $result = $this->budgetRepository->createExpense($budgetEntity);

        $this->assertGreaterThan(0, $result);
    }


    public function testGetById()
    {
        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoStatementMock->method('fetch')->willReturn([
            'id' => 1,
            'amount' => 100.50,
            'purchase_date' => '2025-03-25 10:00:00',
            'description' => 'Compra de materiais',
            'payment_type_id' => 1,
            'category_id' => 2,
            'address_id' => 3
        ]);

        $this->pdoMock->method('prepare')->willReturn($pdoStatementMock);

        $result = $this->budgetRepository->getById(1);

        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Compra de materiais', $result['description']);
    }

    public function testValidateAndUpdate()
    {
        // Dados originais
        $originalData = [
            'amount' => 100.50,
            'purchase_date' => '2025-03-25 10:00:00',
            'description' => 'Compra de materiais'
        ];

        // Dados modificados
        $newData = [
            'amount' => 150.00,
            'purchase_date' => '2025-03-26 10:00:00',
            'description' => 'Compra de equipamentos'
        ];

        // Mock para o PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);

        // Configurar o método 'execute' para retornar true
        $stmtMock->method('execute')
            ->willReturn(true);

        // Mock para o PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Configurar o método 'prepare' para retornar o mock de PDOStatement
        $this->pdoMock->method('prepare')
            ->willReturn($stmtMock);

        // Criar a instância do repositório com o mock do PDO
        $this->budgetRepository = new BudgetRepository($this->pdoMock);

        // Chamar o método que será testado
        $result = $this->budgetRepository->validateAndUpdate($originalData, $newData, 1, 'expenses');

        // Verificar se o resultado foi bem-sucedido
        $this->assertTrue($result['success']);
    }


    public function testDeleteBudget()
    {
        // ID do orçamento a ser deletado
        $expenseId = 1;

        // Criar um mock do PDOStatement
        $pdoStatementMock = $this->createMock(PDOStatement::class);

        // Configurar o comportamento do execute para retornar true (simulando sucesso)
        $pdoStatementMock->method('execute')->willReturn(true);

        // Configurar o mock do PDO para retornar o PDOStatement mockado quando prepare for chamado
        $this->pdoMock->method('prepare')->willReturn($pdoStatementMock);

        // Chamar o método que será testado
        $result = $this->budgetRepository->deleteBudget($expenseId);

        // Verificar o resultado
        $this->assertTrue($result);  // Esperamos que o retorno seja true

        // Verificar se o método prepare foi chamado com a query DELETE correta
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo("DELETE FROM expenses WHERE id = :id"));

        // Verificar se o execute foi chamado com o parâmetro correto (ID do orçamento)
        $pdoStatementMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => $expenseId]));
    }


}
