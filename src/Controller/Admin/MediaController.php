<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route ("/admin/media", name: "admin_media_index", methods: ["GET"])]
    public function index(Request $request, MediaRepository $mediaRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $criteria = [];
        if (!$this->isGranted('ROLE_ADMIN')) {
            $criteria['user'] = $this->getUser();
        }

        $medias = $mediaRepository->findBy(
            $criteria,
            ['id' => 'ASC',],
            25,
            25 * ($page - 1)
        );
        $total = $mediaRepository->count($criteria);

        return $this->render('admin/media/index.html.twig', [
            'medias' => $medias,
            'total' => $total,
            'page' => $page
        ]);
    }

    #[Route ("/admin/media/add", name: "admin_media_add", methods: ["GET", "POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $media->setUser($this->getUser());

            $media->setPath('uploads/' . md5(uniqid()) . '.' . $media->getFile()->guessExtension());
            $media->getFile()->move('uploads/', $media->getPath());
            try {
                $entityManager->persist($media);
                $entityManager->flush();
            } catch (\Exception $e) {
                unlink($media->getPath());
                throw $e;
            }
            return $this->redirectToRoute('admin_media_index');
        }
        return $this->render('admin/media/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route ("/admin/media/delete/{id}", name: "admin_media_delete", methods: ["GET"])]
    public function delete(int $id, MediaRepository $mediaRepository, EntityManagerInterface $entityManager): Response
    {
        $media = $mediaRepository->findOneBy(["id" => $id]);
        if(!$media) {
            throw $this->createNotFoundException('Media introuvable');
        }
        if(!$this->isGranted('ROLE_ADMIN') && $media->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $entityManager->remove($media);
        $entityManager->flush();
        unlink($media->getPath());

        return $this->redirectToRoute('admin_media_index');
    }
}