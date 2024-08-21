<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\TextUI\XmlConfiguration\LogToReportMigration;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 5; $i++){
            $user = new User();
            $user -> setNombre('Usuario' . $i);
            $user -> setEmail('usuario' .$i. '@gmail.com' );
            $user -> setPassword('$2y$13$ReqZTJOBzLNRi1zu0F7Biu1uEpbfSD4ISKEyXCDp5qeTVsinwXfOW');
            $manager -> persist($user);
        }
        $manager->flush();
    }
}
