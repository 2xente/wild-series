<?php

namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController
{
    #[Route('/category/', name : 'app_category_index')]
    public function index(CategoryRepository $categoryRepository):Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', ['categories' => $category]);
    }

    #[Route('/category/{categoryName}', methods : ['GET'], name : 'app_category_show')]
    public function show(string $categoryName, ProgramRepository $programRepository, CategoryRepository $categoryRepository):Response
    {
        $category = $categoryRepository->findOneBy( ['name' => $categoryName]);
        if(!$category){
            throw $this->createNotFoundException('The category does not exist');
        }
        $programs = $programRepository->findBy( ['category' => $category], ['title' =>'DESC'], 3);
      //  dd($programs);
        return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs]);
    }
}