<?php


namespace App\Utils\Manager;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager extends AbstractBaseManager
{
    /**
     * @var string
     */
    private string $productImagesDir;
    /**
     * @var ProductImageManager
     */
    private ProductImageManager $productImageManager;

    public function __construct(EntityManagerInterface $entityManager, string $productImagesDir, ProductImageManager $productImageManager)
    {
        parent::__construct($entityManager);
        $this->productImagesDir = $productImagesDir;
        $this->productImageManager = $productImageManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param object $product
     */
    public function remove(object $product)
    {
        $product->setIsDeleted(true);
        $this->save($product);
    }

    public function getProductImagesDir(Product $product)
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    public function updateProductImages(Product $product, string $tempImageFilename = null)
    {
        if (!$tempImageFilename) {
            return $product;
        }
        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImageManager->saveImageForProduct($productDir, $tempImageFilename);
        $productImage->setProduct($product);
        $product->addProductImage($productImage);
        return $product;
    }

}