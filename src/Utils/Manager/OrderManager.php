<?php

namespace App\Utils\Manager;

use App\Entity\Card;
use App\Entity\CardProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OrderManager extends AbstractBaseManager
{
    private CardManager $cardManager;

    public function __construct(EntityManagerInterface $entityManager, CardManager $cardManager)
    {
        parent::__construct($entityManager);
        $this->cardManager = $cardManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    /**
     * @param string $sessionId
     * @param User $user
     */
    public function createOrderFromCardBySessionId(string $sessionId, User $user)
    {
        $card = $this->cardManager->getRepository()->findOneBy(['sessionId' => $sessionId]);
        if($card) {
            $this->createOrderFromCard($card, $user);
        }
    }

    /**
     * @param Card $card
     * @param User $user
     */
    public function createOrderFromCard(Card $card, User $user)
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);

        $orderTotalPrice = 0;

        /** @var CardProduct $cardProduct */
        foreach ($card->getCardProducts()->getValues() as $cardProduct) {
            $orderProduct = new OrderProduct();
            $orderProduct->setAppOrder($order);
            $orderProduct->setQuantity($cardProduct->getQuantity());
            $orderProduct->setPricePerOne($cardProduct->getProduct()->getPrice());
            $orderProduct->setProduct($cardProduct->getProduct());

            $orderTotalPrice+= $orderProduct->getQuantity() * $orderProduct->getPricePerOne();

            $order->addOrderProduct($orderProduct);
            $this->entityManager->persist($orderProduct);
        }

        $order->setTotalPrice($orderTotalPrice);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->cardManager->remove($card);
    }

    /**
     * @param Order $order
     */
    public function remove(object $order)
    {
        $order->setIsDeleted(true);
        $this->save($order);
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}