<?php


namespace App\Utils\Manager;


use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\FileSystem\FileSystemWorker;
use Doctrine\ORM\EntityManagerInterface;

class ProductImageManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var FileSystemWorker
     */
    private FileSystemWorker $fileSystemWorker;
    /**
     * @var string
     */
    private string $uploadsTempDir;
    /**
     * @var ImageResizer
     */
    private ImageResizer $imageResizer;

    public function __construct(
        EntityManagerInterface $entityManager,
        FileSystemWorker $fileSystemWorker,
        string $uploadsTempDir,
        ImageResizer $imageResizer
    )
    {
        $this->entityManager = $entityManager;
        $this->fileSystemWorker = $fileSystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imageResizer = $imageResizer;
    }

    public function saveImageForProduct(string $productDir, string $tempImageFilename = null)
    {
        if(!$tempImageFilename){
            return null;
        }

        $this->fileSystemWorker->createFolderIfItNotExists($productDir);
        $filenameId = uniqid();

        $imageSmallParams = [
            'width' => 60,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'small')
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageSmallParams);

        $imageMiddleParams = [
            'width' => 430,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'middle')
        ];
        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageMiddleParams);

        $imageBigParams = [
            'width' => 800,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'big')
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageBigParams);

        $productImage = new ProductImage();

        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }

    /**
     * @param ProductImage $productImage
     * @param string $productDir
     */
    public function removeImageFromProduct(ProductImage $productImage, string $productDir)
    {
        $smallFilePath = $productDir.'/'.$productImage->getFilenameSmall();
        $this->fileSystemWorker->remove($smallFilePath);

        $middleFilePath = $productDir.'/'.$productImage->getFilenameMiddle();
        $this->fileSystemWorker->remove($middleFilePath);

        $bigFilePath = $productDir.'/'.$productImage->getFilenameBig();
        $this->fileSystemWorker->remove($bigFilePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }
}