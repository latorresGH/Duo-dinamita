<?php
namespace App\Controller;

use App\Manager\OrdenManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class OrdenController extends AbstractController
{
    private $ordenManager;
      private $security;
   
    public function __construct(OrdenManager $ordenManager, Security $security)
    {
        $this->ordenManager = $ordenManager;
        $this->security = $security;

    }

    #[Route('/orden/agregar', name: 'agregar_producto')]
    public function agregarProducto(Request $request): Response
    {
        $idProducto = $request->request->get('idProducto');
        $cantidad = $request->request->get('cantidad');

        try {
            
            if ((int)$cantidad <= 0) {
            
                throw new \Exception('La cantidad del producto debe ser mayor a 0.');
            }
    
            
            $this->ordenManager->agregarProducto($idProducto, (int) $cantidad, $this->getUser());
    
            
            $this->addFlash('success', 'Se han ingresado ' . $cantidad . ' unidades del producto con ID ' . $idProducto . ' a la orden.');
        } catch (\Exception $e) {
            
            $this->addFlash('error', 'Error al agregar producto a la orden: ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('listar_productos');
        //return $this->redirect('http://localhost/pp1/carrito/public/');
        //return $this->redirect('/listar_productos');

    }

    #[Route('/orden/ver', name: 'ver_orden')]
    public function verOrden(): Response {
        
        $usuario = $this->security->getUser();

        
        if (!$usuario) {
            throw $this->createAccessDeniedException('No estás autenticado');
        }

        
        $orden = $this->ordenManager->verOrden($usuario);
        
        
        return $this->render('orden/resumen.html.twig', [
            'orden' => $orden,
            'usuario' => $usuario
        ]);
    }

    #[Route('/orden/finalizar', name: 'finalizar_compra')]
    public  function finalizarCompra():Response{
        $usuario = $this->security->getUser();

        if (!$usuario) {
            throw $this->createAccessDeniedException('No estás autenticado');
        }

        try{
            $this->ordenManager->finalizarCompra($usuario);
            $this->addFlash('success', 'Compra finalizada con éxito.');
        }catch (\Exception $e){
            $this->addFlash('error',$e->getMessage());
        }
        return $this->redirectToRoute('listar_productos');

    }
    #[Route('/orden/eliminar/{idItem}', name: 'eliminar_item')]
    public function eliminarItem($idItem): Response
    {
        
        $usuario = $this->security->getUser();

        if (!$usuario) {
            throw $this->createAccessDeniedException('No estás autenticado');
        }

        try {
        
            $this->ordenManager->eliminarItem(usuario: $usuario, idItem: $idItem);
            $this->addFlash('success', 'Item eliminado con éxito.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al eliminar el item: ' . $e->getMessage());
        }

        return $this->redirectToRoute('ver_orden');
    }

    #[Route('/orden/vaciar', name: 'vaciar_orden')]
    public function vaciarOrden(): Response
    {
        $usuario = $this->security->getUser();
    
        if (!$usuario) {
            throw $this->createAccessDeniedException('No estás autenticado');
        }
    
        try {
            $this->ordenManager->vaciarOrden($usuario);
            $this->addFlash('success', 'Se ha vaciado la orden correctamente.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error al vaciar la orden: ' . $e->getMessage());
        }
    
        return $this->redirectToRoute('ver_orden'); 
    }
}


