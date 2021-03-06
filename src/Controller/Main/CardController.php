<?php

namespace App\Controller\Main;

use App\Repository\CardRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card", name="main_card_show")
     */
    public function show(Request $request, CardRepository $cardRepository): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $card = $cardRepository->findOneBy(['sessionId' => $phpSessionId]);

        return $this->render('main/card/show.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @Route("/card/create", name="main_card_create")
     */
    public function create(Request $request, OrderManager $orderManager): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $user = $this->getUser();
        $orderManager->createOrderFromCardBySessionId($phpSessionId, $user);
        return $this->redirectToRoute('main_card_show');
    }
}
