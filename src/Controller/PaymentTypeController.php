<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PaymentTypeRepository;
use App\Service\PaymentTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]
class PaymentTypeController extends AbstractController
{


    public function __construct(private readonly PaymentTypeService $paymentTypeService)
    {
    }

    #[Route('/payment_type', methods: ['GET'])]
    public function list()
    {
        try {
            $data = $this->paymentTypeService->get_all_payment_types();
            return $this->json(['success'=>true,'data'=> $data],200);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], 400);
        }
    }
}
