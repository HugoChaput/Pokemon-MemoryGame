<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    //Hashage du mot de passe
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    //Création des utilisateurs
    public function load(ObjectManager $manager): void
    {
        //Administrateur
        $admin=new User();
        $admin->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setPseudo('admin');

        $manager->persist($admin);
        $this->addReference('admin', $admin);

        //Utilisateurs standards
        $user=new User();
        $user->setEmail('user@user.com')
            ->setPassword($this->hasher->hashPassword($user, 'user'))
            ->setPseudo('user');

        $manager->persist($user);
        $this->addReference('user', $user);

        $user=new User();
        $user->setEmail('user2@user.com')
            ->setPassword($this->hasher->hashPassword($user, 'user2'))
            ->setPseudo('user2');

        $manager->persist($user);
        $this->addReference('user2', $user);

        $manager->flush();
    }
}
