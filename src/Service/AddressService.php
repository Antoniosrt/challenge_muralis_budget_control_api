<?php

namespace App\Service;

use App\Entity\AddressEntity;
use App\Repository\AddressRepository;
use App\Repository\BudgetRepository;
use App\Repository\PaymentTypeRepository;

class AddressService
{

    private EntityFactory $entityFactory;

    private AddressRepository $addressRepository;
    private BudgetRepository $budgetRepository;

    public function __construct(AddressRepository $addressRepository, EntityFactory $entityFactory, BudgetRepository $budgetRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->entityFactory = $entityFactory;
        $this->budgetRepository = $budgetRepository;
    }

    public function get_all_address()
    {
        $result= (array) $this->addressRepository->getAll();
        return $result;
    }

    public function get_address_by_id($id){
        return $this->addressRepository->getById($id);
    }

    public function edit($id,array $data){
        $address = $this->addressRepository->getById($id);
        if(!$address){
            return ['success' => false, 'errors' => ['Address not found']];
        }
        $newAddress = $this->budgetRepository->validateAndUpdate($address,$data,$id,"address");
        if(!$newAddress['success'] ){
            return ['error' => $newAddress,'success'=>false];
        }
        return ['success' => true, 'data' => $newAddress];
    }
    public function create_address(array $data){
        $errors=[];
        $address = $this->entityFactory->createFromRequest($data,AddressEntity::class);
        if(!($address instanceof AddressEntity)){
            $errors[] = $address;
        }
        if(count($errors) > 0){
            return ['success' => false, 'errors' => $errors];
        }
        return ['success' => true, 'data' => $this->addressRepository->create($address)];
    }
}