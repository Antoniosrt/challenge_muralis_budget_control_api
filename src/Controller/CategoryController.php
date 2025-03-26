<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PaymentTypeRepository;
use App\Service\AddressService;
use App\Service\CategoryService;
use App\Service\PaymentTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]
class CategoryController extends AbstractController
{


    public function __construct(private readonly PaymentTypeService $paymentTypeService, private readonly AddressService $addressService, private readonly CategoryService $categoryService)
    {
    }

    #[Route('/category', methods: ['GET'])]
    public function list()
    {
        try {
            $data = $this->categoryService->get_all_categories();
            return $this->json(['success'=>true,'data'=> $data],200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }

    #[Route('/category', methods: ['POST'])]
    public function create(Request $request){
        try {
            $data = json_decode($request->getContent(), true);
            $category = $this->categoryService->create_category($data);
            return $this->json(['success'=>true,'data'=> $category],200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
}
