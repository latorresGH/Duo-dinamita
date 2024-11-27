<?php
namespace App\Manager;

use App\Repository\ProductoRepository;
use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductoManager
{
    private $productoRepository;
    public function __construct(ProductoRepository $productoRepository)
    {

        $this->productoRepository = $productoRepository;
    }

    public function getProductos()
    {
        return $this->productoRepository->findAll();
    }

    public function getProducto(int $id): ?Producto
    {
        return $this->productoRepository->find($id);
    }

}
