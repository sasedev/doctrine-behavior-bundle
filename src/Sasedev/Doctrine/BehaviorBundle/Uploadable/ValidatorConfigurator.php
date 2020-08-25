<?php
namespace Sasedev\Doctrine\BehaviorBundle\Uploadable;

use Sasedev\Doctrine\Behavior\Uploadable\Mapping\Validator;

/**
 *
 * @internal
 */
class ValidatorConfigurator
{

    private $validateWritableDirectory;

    /**
     *
     * @param bool $validateWritableDirectory
     */
    public function __construct($validateWritableDirectory)
    {

        $this->validateWritableDirectory = $validateWritableDirectory;

    }

    public function configure()
    {

        Validator::$validateWritableDirectory = $this->validateWritableDirectory;

    }

}
