<?php

namespace App\Service;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Entity\PaymentTypeEntity;
use App\Repository\AddressRepository;
use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Repository\PaymentTypeRepository;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
class BudgetService {
    private BudgetRepository $budgetRepository;
    private PaymentTypeRepository $paymentTypeRepository;
    private EntityFactory $entityFactory;
    private CategoryRepository $categoryRepository;
    private AddressRepository $addressRepository;

    public function __construct(BudgetRepository      $budgetRepository,
                                EntityFactory         $entityFactory,
                                PaymentTypeRepository $paymentTypeRepository, CategoryRepository $categoryRepository, AddressRepository $addressRepository) {
        $this->budgetRepository = $budgetRepository;
        $this->entityFactory = $entityFactory;
        $this->paymentTypeRepository = $paymentTypeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->addressRepository = $addressRepository;
    }

    public function get_budget_by_id(int $id) {
        $row = $this->budgetRepository->getBudgetDataStructure($id);
        // Estruturando os dados em arrays separados
        $row['purchase_date'] = date('Y-m-d', strtotime($row['purchase_date']));
        if ($row) {
            $result = [
                'budget' => [
                    'id' => $row['expense_id'],
                    'amount' => $row['amount'],
                    'purchase_date' => $row['purchase_date'],
                    'description' => $row['description']
                ],
                'payment_type' => [
                    'id' => $row['payment_type_id'],
                    'type' => $row['type']
                ],
                'category' => [
                    'id' => $row['category_id'],
                    'name' => $row['category_name'],
                    'description' => $row['category_description']
                ],
                'address' => [
                    'id' => $row['address_id'],
                    'state' => $row['state'],
                    'city' => $row['city'],
                    'neighborhood' => $row['neighborhood'],
                    'street' => $row['street'],
                    'number' => $row['number'],
                    'complement' => $row['complement']
                ]
            ];
        } else {
            $result = null;
        }
        return ['success'=>true,'data'=>$result];

    }
    public function get_all_budgets(Request $request) {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, (int) $request->query->get('limit', 10));
        //pega somente do mes atual
        $initial_date = date('Y-m-01');
        $data= $this->budgetRepository->getPaginatedExpenses($page, $limit,$initial_date);

        return ['success' => true, 'data' => $data];
    }
    public function create_new_budget(Request $data) {
        $errors=[];
        $data = json_decode($data->getContent(), true);
        $budgetEntity = $this->entityFactory->createFromRequest($data,BudgetEntity::class);
        if(!($budgetEntity instanceof BudgetEntity)){
            $errors[]= $budgetEntity;
        }
        if(count($errors) > 0){
            return ['success' => false, 'errors' => $errors];
        }
        $budgetEntity->setPaymentTypeId($data['payment_type_id']);
        $budgetEntity->setAddressId($data['address_id']);
        $budgetEntity->setCategoryId($data['category_id']);
        return ['success'=>true,'data'=>$this->budgetRepository->createExpense($budgetEntity)];
    }


    public function update_budget(int $id, array $data){
        $error = [];
        $originalBudget = $this->budgetRepository->getById($id);
        if($originalBudget == null){
           return $error[]= ['error' => 'Despesa não encontrada!'];
        }
        $newBudget = $this->budgetRepository->validateAndUpdate($originalBudget,$data, $id,"expenses");
        if(!$newBudget["success"]){
            $error[]=  ['error' => $newBudget,'success'=>false];
        }
//        $newCategory = $this->budgetRepository->validateAndUpdate($originalBudget['category'],$data['category'], $originalBudget['category']['id'],"category");
//        if(!($newCategory instanceof CategoryEntity)){
//            $error[]=  ['error' => $newCategory,'success'=>false];
//        }

        if(count($error) > 0){
            return $error;
        }
        return  $newBudget;

    }

    public function delete_budget(int $id){
        $deleted = $this->budgetRepository->deleteBudget($id);
        if(!$deleted){
            return ['error' => 'Despesa não encontrada!',"success"=>false];
        }

        return ['success'=>true,'data'=>'Despesa deletada com sucesso!'];
    }

}