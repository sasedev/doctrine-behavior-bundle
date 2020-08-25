<?php
namespace Sasedev\Doctrine\BehaviorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:09:32
 */
class ValidateExtensionConfigurationPass implements CompilerPassInterface
{

    /**
     * Validate the DoctrineExtensions DIC extension config.
     *
     * This validation runs in a discrete compiler pass because it depends on
     * DBAL and ODM services, which aren't available during the config merge
     * compiler pass.
     *
     * {@inheritdoc}
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {

        $container->getExtension('sasedev_doctrine_behavior')->configValidate($container);

    }

}

