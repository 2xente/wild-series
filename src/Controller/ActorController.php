<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    #[Route('/actor', name: 'app_actor_index')]
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();

        return $this->render('/actor/index.html.twig', [
            'actors'=> $actors,
        ]);
    }


    #[Route('/actor/{id}', name: 'app_actor_show')]
    public function show(Actor $actors): Response
    {
        $programs = $actors->getPrograms();
        return $this->render('/actor/show.html.twig', ['actors'=>$actors , 'programs' => $programs]);

    }
}