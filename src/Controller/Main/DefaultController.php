<?php

namespace App\Controller\Main;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();
        return $this->render('main/default/index.html.twig', []);
    }

// learn how to create object of entity
//    /**
//     * @Route("/product-add", name="product_add")
//     */
//    public function productAdd(): Response
//    {
//        $product = new Product();
//        $product->setTitle('Product'.rand(1,100));
//        $product->setDescription('smth');
//        $product->setPrice(10);
//        $product->setQuantity(1);
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($product);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('main_homepage');
//    }
}
