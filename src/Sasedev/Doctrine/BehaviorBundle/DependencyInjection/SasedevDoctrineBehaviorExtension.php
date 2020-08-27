<?php
namespace Sasedev\Doctrine\BehaviorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\DependencyInjection\SasedevDoctrineBehaviorExtension
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:20:32
 */
class SasedevDoctrineBehaviorExtension extends Extension
{

    private $entityManagers = [];

    private $documentManagers = [];

    public function load(array $configs, ContainerBuilder $container)
    {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loaded = [];

        $this->entityManagers = $this->processObjectManagerConfigurations($config['orm'], $container, $loader, $loaded, 'doctrine.event_subscriber');
        $this->documentManagers = $this->processObjectManagerConfigurations($config['mongodb'], $container, $loader, $loaded, 'doctrine_mongodb.odm.event_subscriber');

        $container->setParameter('sasedev_doctrine_behavior.default_locale', $config['default_locale']);
        $container->setParameter('sasedev_doctrine_behavior.translation_fallback', $config['translation_fallback']);
        $container->setParameter('sasedev_doctrine_behavior.persist_default_translation', $config['persist_default_translation']);
        $container->setParameter('sasedev_doctrine_behavior.skip_translation_on_load', $config['skip_translation_on_load']);

        // Register the uploadable configuration if the listener is used
        if (isset($loaded['uploadable']))
        {
            $uploadableConfig = $config['uploadable'];

            $container->setParameter('sasedev_doctrine_behavior.default_file_path', $uploadableConfig['default_file_path']);
            $container->setParameter('sasedev_doctrine_behavior.uploadable.default_file_info.class', $uploadableConfig['default_file_info_class']);
            $container->setParameter('sasedev_doctrine_behavior.uploadable.validate_writable_directory', $uploadableConfig['validate_writable_directory']);

            if ($uploadableConfig['default_file_path'])
            {
                $container->getDefinition('sasedev_doctrine_behavior.listener.uploadable')
                    ->addMethodCall('setDefaultPath', [
                    $uploadableConfig['default_file_path']
                ]);
            }

            if ($uploadableConfig['mime_type_guesser_class'])
            {
                if (! class_exists($uploadableConfig['mime_type_guesser_class']))
                {
                    $msg = 'Class "%s" configured to use as the mime type guesser in the Uploadable extension does not exist.';

                    throw new \InvalidArgumentException(sprintf($msg, $uploadableConfig['mime_type_guesser_class']));
                }

                $container->setParameter('sasedev_doctrine_behavior.uploadable.mime_type_guesser.class', $uploadableConfig['mime_type_guesser_class']);
            }
        }

        foreach ($config['class'] as $listener => $class)
        {
            $container->setParameter(sprintf('sasedev_doctrine_behavior.listener.%s.class', $listener), $class);
        }

    }

    /**
     *
     * @internal
     */
    public function configValidate(ContainerBuilder $container)
    {

        foreach ($this->entityManagers as $name)
        {
            if (! $container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name)))
            {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }

        foreach ($this->documentManagers as $name)
        {
            if (! $container->hasDefinition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name)))
            {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }

    }

    /**
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @param array $loaded
     * @param string $doctrineSubscriberTag
     *
     * @return array
     */
    private function processObjectManagerConfigurations(array $configs, ContainerBuilder $container, LoaderInterface $loader, array &$loaded, $doctrineSubscriberTag)
    {

        $usedManagers = [];

        $listenerPriorities = [
            'translatable' => - 10,
            'loggable' => 5,
            'uploadable' => - 5
        ];

        foreach ($configs as $name => $listeners)
        {
            foreach ($listeners as $ext => $enabled)
            {
                if (! $enabled)
                {
                    continue;
                }

                if (! isset($loaded[$ext]))
                {
                    $loader->load($ext . '.xml');
                    $loaded[$ext] = true;
                }

                $attributes = [
                    'connection' => $name
                ];

                if (isset($listenerPriorities[$ext]))
                {
                    $attributes['priority'] = $listenerPriorities[$ext];
                }

                $definition = $container->getDefinition(sprintf('sasedev_doctrine_behavior.listener.%s', $ext));
                $definition->addTag($doctrineSubscriberTag, $attributes);

                $usedManagers[$name] = true;
            }
        }

        return array_keys($usedManagers);

    }

}

