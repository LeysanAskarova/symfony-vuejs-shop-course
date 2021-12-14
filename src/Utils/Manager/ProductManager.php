<?php


namespace App\Utils\Manager;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var string
     */
    private string $productImagesDir;

    public function __construct(EntityManagerInterface $entityManager, string $productImagesDir)
    {
        $this->entityManager = $entityManager;
        $this->productImagesDir = $productImagesDir;
    }

    /**
     * @return ObjectRepository
     */
    public function  getRepository():ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    public  function remove()
    {
        //
    }

    public function getProductImagesDir(Product $product)
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    public function updateProductImages(Product $product, string $tempImageFilename = null)
    {
        if(!$tempImageFilename) {
            return $product;
        }
        $productDir = $this->getProductImagesDir($product);
        dd($productDir);
    }

    /**
     * @param Product $product
     */
    public function save(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

}