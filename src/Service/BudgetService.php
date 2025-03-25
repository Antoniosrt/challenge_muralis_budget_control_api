<?php

namespace App\Service;

use App\Entity\AddressEntity;
use App\Entity\BudgetEntity;
use App\Entity\CategoryEntity;
use App\Repository\BudgetRepository;
use App\Repository\PaymentTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
class BudgetService {
    private BudgetRepository $budgetRepository;
    private PaymentTypeRepository $paymentTypeRepository;
    private EntityFactory $entityFactory;

    public function __construct(BudgetRepository      $budgetRepository,
                                EntityFactory         $entityFactory,
                                DatabaseService       $databaseService,
                                PaymentTypeRepository $paymentTypeRepository) {
        $this->budgetRepository = $budgetRepository;
        $this->entityFactory = $entityFactory;
        $this->paymentTypeRepository = $paymentTypeRepository;
    }
    public function create_new_budget(Request $data) {

        $errors = [];
        $data = json_decode($data->getContent(), true);
        $address = $this->entityFactory->createFromRequest($data['address'],AddressEntity::class);

        if(!($address instanceof AddressEntity)){
            $errors[] = $address;
        }
        $category = $this->entityFactory->createFromRequest($data['category'],CategoryEntity::class);
        if(!($category instanceof CategoryEntity)){
            $errors[] = $category;
        }
        $budgetEntity = $this->entityFactory->createFromRequest($data['budget'],BudgetEntity::class);
        if(!($budgetEntity instanceof BudgetEntity)){
            $errors[] = $budgetEntity;
        }
        if($data['payment_type_id'] == null){
            $errors[] = ['error' => 'Tipo de pagamento é obrigatorio!'];
        }else{
            $paymentType = $this->paymentTypeRepository->getById($data['payment_type_id']);
            if($paymentType == null){
                $errors[] = ['error' => 'Tipo de pagamento não encontrado!'];
            }
        }
        if(count($errors) > 0){
            return $errors;
        }

        return $this->budgetRepository->createExpense($budgetEntity,$address,$category,$data['payment_type_id']);
    }


    public function update_budget(int $id, array $data){

        $originalBudget = $this->budgetRepository->getById($id);
        if($originalBudget == null){
            return ['error' => 'Despesa não encontrada!'];
        }
        $newBudget = $this->entityFactory->createFromRequest($data,BudgetEntity::class);

        if(!($newBudget instanceof BudgetEntity)){

        }

    }
}