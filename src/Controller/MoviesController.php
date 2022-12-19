<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class MoviesController extends AbstractController
{

    #[Route('/movie', name: 'app_movies')]
    public function index(MoviesRepository $moviesRepository): Response
    {
        $movies = $moviesRepository->findAll();
        return $this->render('movie/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/bydate', name:'app_bydate')]
    public function bydate(MoviesRepository $moviesRepository):Response {

        $movies = $moviesRepository->findBy([],['createdAt' => 'DESC']);
        return $this->render('movie/index.html.twig', ['movies' => $movies]);

    }

    #[Route('/movie/create', name: 'app_create')]
    public function createMovie():Response
    {
        return $this->render('movie/create.html.twig');
    }
}
