<?php
// src/Controller/ProgramController.php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'app_program_index')]
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }


    #[Route('/program/{id}/', requirements : ['id'=>'\d+'], methods : ['GET'], name : 'app_program_show')]
    public function show( int $id): Response
    {
        return $this->render('program/show.html.twig', ['id' => $id] );
    }
}