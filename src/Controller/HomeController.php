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
    public function guest(int $id, UserRepository $userRepository): Response
    {
        $guest = $userRepository->findOneGuestWithMedia($id);
        return $this->render('front/guest.html.twig', [
            'guest' => $guest,
        ]);
    }

    #[Route ("/portfolio/{id}", name: "portfolio")]
    public function portfolio(AlbumRepository $albumRepository, UserRepository $userRepository, MediaRepository $mediaRepository, ?int $id = null): Response
    {
        $albums = $albumRepository->findAll();
        $album = $id ? $albumRepository->find($id) : null;
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