<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class AlbumController extends AbstractController
{
    #[Route ("/admin/album", name: "admin_album_index", methods: ["GET"])]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();
        return $this->render('admin/album/index.html.twig', ['albums' => $albums]);
    }

    #[Route ("/admin/album/add", name: "admin_album_add", methods: ["GET", "POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($album);
            $entityManager->flush();
            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route ("/admin/album/update/{id}", name: "admin_album_update", methods: ["GET", "POST"])]
    public function update(Request $request, int $id, AlbumRepository $albumRepository, EntityManagerInterface $entityManager): Response
    {
        $album = $albumRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($album);
            $entityManager->flush();
            return $this->redirectToRoute('admin_album_index');
        }
        return $this->render('admin/album/update.html.twig', ['form' => $form->createView()]);
    }

    #[Route ("/admin/album/delete/{id}", name: "admin_album_delete", methods: ["GET"])]
    public function delete(int $id, EntityManagerInterface $entityManager, AlbumRepository $albumRepository,MediaRepository $mediaRepository): Response
    {
        $album = $albumRepository->findOneBy(["id" => $id]);
        if(!$album){
            return $this->redirectToRoute('admin_album_index');
        }
        $medias = $mediaRepository->findBy(["album" => $id]);
        foreach ($medias as $media){
            unlink($media->getPath());
            $entityManager->remove($media);
        }
        $entityManager->remove($album);
        $entityManager->flush();
        return $this->redirectToRoute('admin_album_index');
    }
}