<?php
namespace Sasedev\Doctrine\BehaviorBundle\Uploadable;

use Sasedev\Doctrine\Behavior\Uploadable\UploadableListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadableManager
{

    /** @var \Sasedev\Doctrine\Behavior\Uploadable\UploadableListener */
    private $listener;

    private $fileInfoClass;

    public function __construct(UploadableListener $listener, $fileInfoClass)
    {

        $this->listener = $listener;
        $this->fileInfoClass = $fileInfoClass;

    }

    /**
     * This method marks an entity to be uploaded as soon as the "flush" method of your object manager is called.
     * After calling this method, the file info you passed is set for this entity in the listener. This is all it takes
     * to upload a file for an entity in the Uploadable extension.
     *
     * @param object $entity - The entity you are marking to "Upload" as soon as you call "flush".
     * @param mixed $fileInfo - The file info object or array. In Symfony, this will be typically an UploadedFile instance.
     */
    public function markEntityToUpload($entity, $fileInfo)
    {

        if (is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $fileInfoClass = $this->fileInfoClass;

            $fileInfo = new $fileInfoClass($fileInfo);
        }

        $this->listener->addEntityFileInfo($entity, $fileInfo);

    }

    /**
     *
     * @return \Sasedev\Doctrine\Behavior\Uploadable\UploadableListener
     */
    public function getUploadableListener()
    {

        return $this->listener;

    }

}
