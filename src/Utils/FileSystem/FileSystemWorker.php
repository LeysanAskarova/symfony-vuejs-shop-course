<?php


namespace App\Utils\FileSystem;


use Symfony\Component\Filesystem\Filesystem;

class FileSystemWorker
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $folder
     */
    public function createFolderIfItNotExists(string $folder)
    {
        if(!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
    }

    /**
     * @param string $item
     */
    public function remove(string $item)
    {
        if($this->filesystem->exists($item)) {
            $this->filesystem->remove($item);
        }
    }

}