<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\AlbumFactory;
use App\Factory\MediaFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::createOne([
            'roles' => ["ROLE_ADMIN"],
            'name' => "Ina Zaoui",
            'email' => "ina@zaoui.com",
            'password' => $this->passwordHasher->hashPassword(new User(), 'password')]);

        $users = UserFactory::createMany(50, function (): array {
            static $index = 0;
            $index++;
            return [
                'description' => 'Description de l\'utilisateur ' . $index,
                'name' => 'Utilisateur ' . $index,
                'email' => 'user' . $index . '@example.com',
                'password' => $this->passwordHasher->hashPassword(new User(), 'password')
            ];
        });

        $albums = AlbumFactory::createMany(5, function (): array {
            static $index = 1;
            return [
                'name' => "Album " . $index++
            ];
        });

        /*
        Creation of admin's media
        Création des médias de l'admin
        */
        $adminMedias = MediaFactory::createMany(50, function () use ($admin, $albums): array {
            static $pathIndex = 1600;
            $title = "Titre " . $pathIndex . ".jpg";
            $baseFilePath = "uploads/";
            $path = $baseFilePath . $pathIndex . ".jpg";
            $album = $albums[$pathIndex % count($albums)];
            $pathIndex++;

            return [
                'user' => $admin,
                'album' => $album,
                'path' => $path,
                'title' => $title,
            ];
        });

        /*
        Creation of other users' media
        Création des autres médias*/
        MediaFactory::createMany(500, function () use ($users): array {
            static $mediaIndex = 0;
            $userIndex = $mediaIndex % count($users); // Répartit les médias équitablement entre les utilisateurs
            $user = $users[$userIndex];
            $baseFilePath = "uploads/";
            $paths = [];
            for ($i = 0; $i < 50; $i++) {
                $paths[] = $baseFilePath . $i . ".jpg";
            }
            $path = $paths[$mediaIndex % count($paths)];
            $mediaIndex++;

            return [
                'user' => $user,
                'path' => $path,
                'title' => "Titre " . $mediaIndex,
            ];
        });

        $manager->flush();
    }
}
