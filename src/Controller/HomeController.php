<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route("/", name: "home")]
    public function home():Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route ("/guests", name: "public_guests")]
    public function guests(UserRepository $userRepository): Response
    {
        $guests = $userRepository->findAllGuestsWithMedia();
        return $this->render('front/guests.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route ("/guests/{id}", name: "guest")]
    public function guest(User $guest, UserRepository $userRepository): Response
    {
        return $this->render('front/guest.html.twig', [
            'guest' => $guest,
        ]);
    }

    #[Route ("/portfolio/{albumId}", name: "portfolio")]
    public function portfolio(AlbumRepository $albumRepository, UserRepository $userRepository, MediaRepository $mediaRepository, ?int $albumId = null): Response
    {
        $albums = $albumRepository->findAll();
        $album = $albumId ? $albumRepository->find($albumId) : null;
        $user = $userRepository->findAdmin();
        if($album)
        {
            $medias = $mediaRepository->findBy(["album" => $album]);
        }
        else
        {
            if($user)
            $medias = $mediaRepository->findBy(["user" => $user->getId()]);
        }
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias ?? []
        ]);
    }

    #[Route ("/about", name: "about")]
    public function about():Response
    {
        return $this->render('front/about.html.twig');
    }
}