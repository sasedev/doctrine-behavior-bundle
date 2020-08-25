<?php
namespace Sasedev\Doctrine\BehaviorBundle;

use Sasedev\Doctrine\BehaviorBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\SasedevDoctrineBehaviorBundle
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:04:47
 */
class SasedevDoctrineBehaviorBundle extends Bundle
{

    /**
     *
     * {@inheritdoc}
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}

