<?php
namespace App\Controller;

use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/api')]
class BudgetController extends AbstractController
{
    private BudgetService $budgetService;
    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }
    #[Route('/despesas', methods: ['GET', 'HEAD'])]
    public function show(): JsonResponse
    {
        return $this->json([
            'message' => 'Hello, Symfony 7 API!',
            'timestamp' => time()
        ]);
    }

    #[Route('/despesas', methods: ['PUT'])]
    public function edit(int $id,Request $request): JsonResponse
    {
        try {
            return $this->budgetService->update_budget($id,$request);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage(),'success'=>false], 400);
        }
    }

    #[Route('/despesas',methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $response = $this->budgetService->create_new_budget($request);
        return $this->json($response);
    }

    

}