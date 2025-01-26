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
        $users = UserFactory::createMany(49);

        $users = array_merge([$admin], $users);

        $albums = AlbumFactory::createMany(5, fn(): array => [
            'name' => "Album " . random_int(1, 5),
        ]);

        /* Création des médias de l'admin */
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

        /* Création des autres médias*/
        MediaFactory::createMany(500, function () use ($users, $albums): array {
            static $pathIndex = 0;

            $user = $users[array_rand($users)];
            $baseFilePath = "uploads/";
            $paths = [];
            for ($i = 0; $i < 50; $i++) {
                $paths[] = $baseFilePath . $i . ".jpg";
            }
            $path = $paths[$pathIndex % count($paths)];
            $pathIndex++;

            return [
                'user' => $user,
                'path' => $path,
                'title' => "Titre " . $pathIndex,
            ];
        });

        $manager->flush();
    }
}
