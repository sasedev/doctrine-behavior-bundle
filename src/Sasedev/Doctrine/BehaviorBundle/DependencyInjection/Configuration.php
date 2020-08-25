<?php
namespace Sasedev\Doctrine\BehaviorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 *
 * Sasedev\Doctrine\BehaviorBundle\DependencyInjection\Configuration
 *
 *
 * @author sasedev <sinus@sasedev.net>
 *         Created on: 4 mai 2020 @ 10:14:38
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder('sasedev_doctrine_behavior');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->getVendorNode('orm'))
            ->append($this->getVendorNode('mongodb'))
            ->append($this->getClassNode())
            ->append($this->getUploadableNode())
            ->children()
            ->scalarNode('default_locale')
            ->cannotBeEmpty()
            ->defaultValue('en')
            ->end()
            ->booleanNode('translation_fallback')
            ->defaultFalse()
            ->end()
            ->booleanNode('persist_default_translation')
            ->defaultFalse()
            ->end()
            ->booleanNode('skip_translation_on_load')
            ->defaultFalse()
            ->end()
            ->end();

        return $treeBuilder;

    }

    /**
     *
     * @param string $name
     */
    private function getVendorNode($name)
    {

        $treeBuilder = new TreeBuilder($name);
        $node = $treeBuilder->getRootNode();

        $node->useAttributeAsKey('id')
            ->prototype('array')
            ->children()
            ->scalarNode('translatable')
            ->defaultFalse()
            ->end()
            ->scalarNode('timestampable')
            ->defaultFalse()
            ->end()
            ->scalarNode('blameable')
            ->defaultFalse()
            ->end()
            ->scalarNode('sluggable')
            ->defaultFalse()
            ->end()
            ->scalarNode('tree')
            ->defaultFalse()
            ->end()
            ->scalarNode('loggable')
            ->defaultFalse()
            ->end()
            ->scalarNode('sortable')
            ->defaultFalse()
            ->end()
            ->scalarNode('softdeleteable')
            ->defaultFalse()
            ->end()
            ->scalarNode('uploadable')
            ->defaultFalse()
            ->end()
            ->scalarNode('reference_integrity')
            ->defaultFalse()
            ->end()
            ->end()
            ->end();

        return $node;

    }

    private function getClassNode()
    {

        $treeBuilder = new TreeBuilder('class');
        $node = $treeBuilder->getRootNode();

        $node->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('translatable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Translatable\\TranslatableListener')
            ->end()
            ->scalarNode('timestampable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Timestampable\\TimestampableListener')
            ->end()
            ->scalarNode('blameable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Blameable\\BlameableListener')
            ->end()
            ->scalarNode('sluggable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Sluggable\\SluggableListener')
            ->end()
            ->scalarNode('tree')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Tree\\TreeListener')
            ->end()
            ->scalarNode('loggable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Loggable\\LoggableListener')
            ->end()
            ->scalarNode('sortable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Sortable\\SortableListener')
            ->end()
            ->scalarNode('softdeleteable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\SoftDeleteable\\SoftDeleteableListener')
            ->end()
            ->scalarNode('uploadable')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\Uploadable\\UploadableListener')
            ->end()
            ->scalarNode('reference_integrity')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\Behavior\\ReferenceIntegrity\\ReferenceIntegrityListener')
            ->end()
            ->end();

        return $node;

    }

    private function getUploadableNode()
    {

        $treeBuilder = new TreeBuilder('uploadable');
        $node = $treeBuilder->getRootNode();

        $node->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('default_file_path')
            ->cannotBeEmpty()
            ->defaultNull()
            ->end()
            ->scalarNode('mime_type_guesser_class')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\\BehaviorBundle\\Uploadable\\MimeTypeGuesserAdapter')
            ->end()
            ->scalarNode('default_file_info_class')
            ->cannotBeEmpty()
            ->defaultValue('Sasedev\\Doctrine\BehaviorBundle\\Uploadable\\UploadedFileInfo')
            ->end()
            ->booleanNode('validate_writable_directory')
            ->defaultTrue()
            ->end()
            ->end();

        return $node;

    }

}

