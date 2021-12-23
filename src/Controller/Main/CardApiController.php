<?php

namespace App\Controller\Main;

use App\Entity\Card;
use App\Entity\CardProduct;
use App\Repository\CardProductRepository;
use App\Repository\CardRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="main_api_")
 */
class CardApiController extends AbstractController
{
    /**
     * @Route("/card", methods={"POST"}, name="card_save")
     */
    public function saveCard(
        Request $request,
        CardRepository $cardRepository,
        ProductRepository $productRepository,
        CardProductRepository $cardProductRepository
    ): Response
    {
        $productId = $request->request->get('productId');
        $phpSessionId = $request->cookies->get('PHPSESSID');

        $product = $productRepository->findOneBy(['uuid' => $productId]);

        $card = $cardRepository->findOneBy(['sessionId' => $phpSessionId]);
        if(!$card) {
            $card = new Card();
            $card->setSessionId($phpSessionId);
        }

        $cardProduct = $cardProductRepository->findOneBy(['card'=>$card, 'product' => $product]);
        if(!$cardProduct) {
            $cardProduct = new CardProduct();
            $cardProduct->setCard($card);
            $cardProduct->setQuantity(1);
            $cardProduct->setProduct($product);

            $card->addCardProduct($cardProduct);
        } else {
            $quantity = $cardProduct->getQuantity() + 1;
            $cardProduct->setQuantity($quantity);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($card);
        $entityManager->persist($cardProduct);
        $entityManager->flush();

        return new JsonResponse([
            'success' => false,
            'data' => [
                'test' => 123
            ]
        ]);
    }
}
