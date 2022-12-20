<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Form\MoviesFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;


class MoviesController extends AbstractController
{
    private $em;
    private $token;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->token = $token;
    }

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

    #[Route('/byuser/{id}', name: 'app_byuser')]
    public function byuser(MoviesRepository $moviesRepository, $id):Response
    {
        $movies = $moviesRepository->findBY(['user' => $id] );
        return $this->render('movie/index.html.twig', ['movies' => $movies]);
    }

    #[Route('/movie/create', name: 'app_create')]
    public function createMovie(Request $request):Response
    {
        $movie = new Movies();
        $form = $this->createForm(MoviesFormType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newMovie = $form->getData();
            $current = $this->token->getToken()->getUser();
            $newMovie->setUser($current);
            $newMovie->setCreatedAt(new \DateTimeImmutable());
            $newMovie->setVotes(0);

            $this->em->persist($newMovie);
            $this->em->flush();

            $this->addFlash('success', 'movie has been submitted!');
            return $this->redirectToRoute('app_create');

        }

        return $this->render('movie/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/movie/{id}/vote', name: 'app_movie_vote', methods: ['POST'])]
    public function vote(Movies $movies, Request $request, EntityManagerInterface $entityManager):Response
    {
        $direction = $request->request->get('direction', 'like');
        if($direction === 'like'){
            $movies->likeVote();//custom function 
        } else{
            $movies->dislikeVote();//custom function
        }

        $entityManager->persist($movies);
        $entityManager->flush();

        return $this->redirectToRoute('app_movies');
    }
}
