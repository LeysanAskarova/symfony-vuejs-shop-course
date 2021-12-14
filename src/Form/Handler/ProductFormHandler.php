<?php


namespace App\Form\Handler;


use App\Entity\Product;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\Form;

class ProductFormHandler
{
    /**
     * @var ProductManager
     */
    private ProductManager $productManager;
    /**
     * @var FileSaver
     */
    private FileSaver $fileSaver;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->productManager = $productManager;
        $this->fileSaver = $fileSaver;
    }

    /**
     * @param Product $product
     * @param Form $form
     * @return Product
     */
    public function processEditForm(Product $product, Form $form)
    {
        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();

        $tempImageFilename = $newImageFile
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFilename);

        $this->productManager->save($product);

        return $product;
    }
}