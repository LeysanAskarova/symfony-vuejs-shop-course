<?php


namespace App\Utils\Manager;


use App\Entity\Card;
use Doctrine\Persistence\ObjectRepository;

class CardManager extends AbstractBaseManager
{

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Card::class);
    }
}