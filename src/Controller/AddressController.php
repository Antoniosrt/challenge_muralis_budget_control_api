<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PaymentTypeRepository;
use App\Service\AddressService;
use App\Service\PaymentTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]
class AddressController extends AbstractController
{


    public function __construct(private readonly PaymentTypeService $paymentTypeService, private readonly AddressService $addressService)
    {
    }

    #[Route('/address', methods: ['GET'])]
    public function list()
    {
        try {
            $data = $this->addressService->get_all_address();
            return $this->json(['success'=>true,'data'=> $data],200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
    #[Route('/address/{id}', methods: ['GET'])]
    public function show(int $id)
    {
        try {
            $data = $this->addressService->get_address_by_id($id);
            return $this->json(['success'=>true,'data'=> $data],200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
    #[Route('/address/{id}', methods: ['PUT'])]
    public function edit(int $id,Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data = $this->addressService->edit($id,$data);
            return $this->json($data,200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
    #[Route('/address', methods: ['Post'])]
    public function create(Request $request){
        try{
            $data = json_decode($request->getContent(), true);
            $response = $this->addressService->create_address($data);
            return $this->json($response);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
}
