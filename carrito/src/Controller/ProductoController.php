<?php
namespace App\Controller;

use App\Manager\ProductoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ProductoController extends AbstractController
{
    private $productoManager;
    public function __construct(ProductoManager $productoManager){
        $this-> productoManager = $productoManager;

    }
#[Route('/', name: 'listar_productos')]
public function listarProductos(): Response
{
    $productos = $this->productoManager->getProductos();


 return $this->render('lista.html.twig',['productos'=>$productos]);
}

#[Route('/producto/{idProducto}', name: 'detalle_producto')]
public function detalle_producto($idProducto): Response
{
    $producto = $this->productoManager->getProducto($idProducto);
    return $this->render('detalle.html.twig',['producto'=>$producto]);
}
} 