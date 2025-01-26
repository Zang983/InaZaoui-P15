<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\GuestType;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class GuestController extends AbstractController
{
    #[Route('/admin/guests', name: 'app_admin_guest')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/guests/index.html.twig', [
            'controller_name' => 'GuestController',
            'users' => $users,
        ]);
    }

    #[Route('/admin/guests/delete/{id}', name: 'admin_delete_guest')]
    public function deleteGuest(
        int                    $id,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager,
        MediaRepository        $mediaRepository
    ): Response
    {
        $user = $userRepository->findOneBy(["id" => $id]);
        if (!$user) {
            return $this->redirectToRoute('app_admin_guest');
        }
        $medias = $mediaRepository->findBy(["user" => $user->getId()]);
        if ($medias) {
            foreach ($medias as $media) {
                unlink($media->getPath());
                $entityManager->remove($media);
            }
            $entityManager->flush();
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_guest');
    }

    #[Route('admin/guests/block/{id}', name: 'admin_block_guest')]
    public function blockGuest(
        int                    $id,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $userRepository->findOneBy(["id" => $id]);
        if (!$user) {
            return $this->redirectToRoute('app_admin_guest');
        }
        $user->setIsBlocked(!$user->isBlocked());
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_guest');
    }

    #[Route('admin/guests/add', name: 'admin_add_guest')]
    public function addGuest(
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $form = $this->createForm(GuestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setName($form->get('name')->getData());
            $user->setDescription($form->get('description')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setAdmin($form->get('admin')->getData());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
            $user->setIsBlocked(false);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_guest');
        }
        return $this->render('admin/guests/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
