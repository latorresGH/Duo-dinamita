<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductoRepository;
use App\Entity\Orden;
use App\Repository\OrdenRepository;
use App\Repository\ItemRepository;
use App\Entity\User;


class OrdenManager
{
    private EntityManagerInterface $entityManager;
    private ProductoRepository $productoRepository;
    private OrdenRepository $ordenRepository;
    private ItemRepository $itemRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductoRepository $productoRepository, OrdenRepository $ordenRepository, ItemRepository $itemRepository)
    {
        $this->entityManager = $entityManager;
        $this->productoRepository = $productoRepository;
        $this->ordenRepository = $ordenRepository;
        $this->itemRepository = $itemRepository;
    }

    public function agregarProducto($idProducto, $cantidad, $usuario)
    {
        // $orden = new Orden();
        // $orden->setEstado('Iniciada');
        // $orden->setIniciada(new \DateTime());
        // $orden->setUsuario($usuario);

        $orden = $this->obtenerOrden($usuario, 'Iniciada');

        $producto = $this->productoRepository->find($idProducto);

        $item = $orden->agregarItem($producto, $cantidad);

        $this->entityManager->persist($item);
        $this->entityManager->persist($orden);
        $this->entityManager->flush();
    }
    private function obtenerOrden($usuario, string $estado = 'Iniciada')
    {

        $orden = $this->ordenRepository->findOneBy([
            'usuario' => $usuario,
            'estado' => $estado
        ]);

        if (!$orden) {
            $orden = new Orden();
            $orden->setEstado($estado);
            $orden->setIniciada(new \DateTime());
            $orden->setUsuario($usuario);
        }
        return $orden;
    }

    public function verOrden($usuario)
    {
        $orden = $this->obtenerOrden($usuario, 'Iniciada');
        
        return $orden;
    }
    public function finalizarCompra($usuario)
    {
        $orden = $this->ordenRepository->findOneBy([
            'usuario' => $usuario,
            'estado' => 'Iniciada',
        ]);

        if (!$orden) {
            throw new \Exception('No hay una orden iniciada para este usuario.');
        }

       
        $orden->setEstado('Finalizada');
        $orden->setIniciada(new \DateTime());

       
        $this->entityManager->persist($orden);
        $this->entityManager->flush();
    }
    public function eliminarItem(User $usuario, $idItem)
    {
        $item = $this->itemRepository->find($idItem);

        if ($item) {
            
            $orden = $item->getOrden();

            if ($orden && $orden->getUsuario() === $usuario) {
                
                $this->entityManager->remove($item);
                $this->entityManager->flush();
            } else {
                throw new \Exception('El item no pertenece al usuario o la orden no es válida.');
            }
        } else {
            throw new \Exception('Item no encontrado.');
        }
    }
    public function vaciarOrden(User $usuario)
    {
        $orden = $this->ordenRepository->findOneBy([
            'usuario' => $usuario,
            'estado' => 'Iniciada',
        ]);

        if (!$orden) {
            throw new \Exception('No hay una orden iniciada para este usuario.');
        }
    
        foreach ($orden->getItems() as $item) {
            $orden->removeItem($item);  
            $this->entityManager->remove($item); 
        }
    
        $this->entityManager->flush(); 
    }
}