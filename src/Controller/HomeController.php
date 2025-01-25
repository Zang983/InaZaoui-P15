<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route("/", name: "home")]
    public function home()
    {
        return $this->render('front/home.html.twig');
    }

    #[Route ("/guests", name: "guests")]
    public function guests(UserRepository $userRepository)
    {
        $guests = $userRepository->findBy(["admin" => false]);
        return $this->render('front/guests.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route ("/guest/{id}", name: "guest")]
    public function guest(int $id, UserRepository $userRepository)
    {
        $guest = $userRepository->findOneBy(["id" => $id]);
        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }

    #[Route ("/portfolio/{id}", name: "portfolio")]
    public function portfolio(AlbumRepository $albumRepository, UserRepository $userRepository, MediaRepository $mediaRepository, ?int $id = null)
    {
        $albums = $albumRepository->findAll();
        $album = $id ? $albumRepository->find($id) : null;
        $user = $userRepository->findBy(["admin" => true]);

        $medias = $album
            ? $mediaRepository->findBy(["album" => $album])
            : $mediaRepository->findBy(["user" => $user]);
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }
    
    #[Route ("/about", name: "about")]
    public function about()
    {
        return $this->render('front/about.html.twig');
    }
}