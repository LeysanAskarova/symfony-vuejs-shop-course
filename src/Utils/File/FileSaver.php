<?php


namespace App\Utils\File;


use App\Utils\FileSystem\FileSystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    /**
     * @var string
     */
    private string $uploadsTempDir;
    /**
     * @var FileSystemWorker
     */
    private FileSystemWorker $fileSystemWorker;
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    public function __construct(string $uploadsTempDir, FileSystemWorker $fileSystemWorker, SluggerInterface $slugger)
    {
        $this->uploadsTempDir = $uploadsTempDir;
        $this->fileSystemWorker = $fileSystemWorker;
        $this->slugger = $slugger;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return null
     */
    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile)
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        $fileName = sprintf('%s-%s.%s', $safeFilename, uniqid(), $uploadedFile->getClientOriginalExtension());

        $this->fileSystemWorker->createFolderIfItNotExists($this->uploadsTempDir);

        try {
            $uploadedFile->move($this->uploadsTempDir, $fileName);
        }
        catch (FileException $exception) {
            return null;
        }

        return $fileName;
    }
}