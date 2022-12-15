<?php
// src/Controller/ProgramController.php
namespace App\Controller;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\ProgramDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramController extends AbstractController
{

    #[Route('/program/', name: 'app_program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/program/new', name :'app_program_new')]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {

        $program = new Program();


        $form= $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $programRepository->save($program, true);

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('app_program_index');
        }

        return $this->renderForm('program/new.html.twig', ['form' => $form]);
    }

    #[Route('/program/{id}/', methods : ['GET'], name : 'app_program_show')]
    public function show(Program $program, SeasonRepository $seasonRepository, ProgramDuration $programDuration ): Response
    {
        $seasons = $seasonRepository->findBy(['program'=> $program]);


        return $this->render('program/show.html.twig', [
            'program' => $program ,
            'seasons' => $seasons,
            'programDuration' => $programDuration->calculate($program)
            ] );
    }

    #[Route ('/program/{program}/season/{season}', name: 'app_program_season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response
    {

        $episodes = $episodeRepository->findBy(['season' => $season], ['number' => 'ASC']);
        return $this->render('program/season_show.html.twig', ['program'=> $program, 'season'=> $season, 'episodes'=> $episodes]);
    }


    #[Route ('program/{programId}/season/{seasonId}/episode/{episodeId}', name :'app_program_episode_show')]
    #[Entity('program', options:['mapping'=> ['programId'=> 'id']])]
    #[Entity('season', options:['mapping'=> ['seasonId'=> 'id']])]
    #[Entity('episode', options:['mapping'=> ['episodeId'=> 'id']])]
    public function ShowEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', ['program'=> $program, 'season'=> $season, 'episode'=> $episode]);
    }
}