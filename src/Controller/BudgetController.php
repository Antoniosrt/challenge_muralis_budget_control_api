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
    public function show(Request $request): JsonResponse
    {
        try {

            return $this->json($this->budgetService->get_all_budgets($request));
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage(),'success'=>false], 400);
        }
    }

    #[Route('/despesas/{id}', methods: ['GET'])]
    public function view(int $id,Request $request): JsonResponse
    {
        try {
            return $this->json($this->budgetService->get_budget_by_id($id));
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage(),'success'=>false], 400);
        }
    }
    #[Route('/despesas/{id}', methods: ['PUT'])]
    public function edit(int $id,Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            return $this->json($this->budgetService->update_budget($id,$data));
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage(),'success'=>false], 400);
        }
    }

    #[Route('/despesas',methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $response = $this->budgetService->create_new_budget($request);
        return $this->json($response);
    }

    #[Route('/despesas/{id}', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        try {
            $deleteBudget = $this->budgetService->delete_budget($id);
            return $this->json($deleteBudget);
        }catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage(),'success'=>false], 400);
        }
    }

}