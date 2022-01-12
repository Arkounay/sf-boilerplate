<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OtherFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 2; $i++) {
            $contact = (new Contact())
                ->setFirstname($faker->firstName)
                ->setLastname($faker->firstName)
                ->setEmail($faker->email)
                ->setMessage($faker->text);
            $manager->persist($contact);
        }

        $manager->flush();
    }
}
