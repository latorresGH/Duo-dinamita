<?php

namespace App\DataFixtures;

use App\Entity\Producto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\TextUI\XmlConfiguration\LogToReportMigration;

class ProductoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 10; $i++){
            $producto = new Producto();
            $producto->setNombre('Producto '.$i);
            $producto->setDescripcion(' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies pellentesque aliquam. ');
            $producto->setPrecio(mt_rand(10,100));
            $producto->setImagen('images/producto'.$i.'.jpg');
            $manager->persist($producto);
        }
        
        $manager->flush();
    }
}
