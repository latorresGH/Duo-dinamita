<?php
namespace App\Controller;

use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ProductoController extends AbstractController
{
    private $productoRepository;
    public function __construct(ProductoRepository $productoRepository){
        $this-> productoRepository = $productoRepository;

    }
#[Route('/', name: 'listar_productos')]
public function listarProductos(): Response
{
    $producto = $this->productoRepository->findAll();

 return $this->render('lista.html.twig',['productos'=>$producto]);
}
} 