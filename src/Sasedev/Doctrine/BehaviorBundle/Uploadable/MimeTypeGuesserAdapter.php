<?php
namespace Sasedev\Doctrine\BehaviorBundle\Uploadable;

use Sasedev\Doctrine\Behavior\Uploadable\MimeType\MimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypes;

class MimeTypeGuesserAdapter implements MimeTypeGuesserInterface
{

    public function guess($filePath)
    {

        $guesser = new MimeTypes();
        $guesser->guessMimeType($filePath);

    }

}
