<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //create 20 users
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername('user' . $i);
            $user->setFirstName('First' . $i);
            $user->setLastName('Last' . $i);
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword('test1234');
            $user->setProfilePicture('/profile' . $i . '.jpg');
            $user->setBio('Bio for user' . $i);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
        }
        $manager->flush();
    }
}