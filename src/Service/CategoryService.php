<?php

namespace App\Service;

use App\Entity\AddressEntity;
use App\Entity\CategoryEntity;
use App\Repository\CategoryRepository;

class CategoryService
{

    private EntityFactory $entityFactory;

    private CategoryRepository $categoryRepository;

    public function __construct( EntityFactory $entityFactory, CategoryRepository $categoryRepository)
    {
        $this->entityFactory = $entityFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function get_all_categories()
    {
        return $this->categoryRepository->getAll();
    }

    public function get_category_by_id($id){
        return $this->categoryRepository->getById($id);
    }

    public function create_category(array $data){
        $errors=[];
        $category = $this->entityFactory->createFromRequest($data,CategoryEntity::class);

        if(!($category instanceof CategoryEntity)){
            $errors[] = $category;
        }
        if(count($errors) > 0){
            return $errors;
        }
        return $this->categoryRepository->create($category);
    }
}