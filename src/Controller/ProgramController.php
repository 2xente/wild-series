<?php
// src/Controller/ProgramController.php
namespace App\Controller;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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


    #[Route('/program/{id}/', requirements : ['id'=>'\d+'], methods : ['GET'], name : 'app_program_show')]
    public function show( int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository ): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        $seasons = $seasonRepository->findBy(['program'=> $program]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', ['program' => $program , 'seasons' => $seasons] );
    }

    #[Route ('/program/{programId}/seasons/{seasonId}', name: 'app_program_season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findBy(['number' => $seasonId]);
        $episodes = $episodeRepository->findBy(['season'=> $season]);
       // dd($season);

        return $this->render('program/season_show.html.twig', ['program'=> $program, 'season'=> $season, 'episodes'=> $episodes]);
    }
}