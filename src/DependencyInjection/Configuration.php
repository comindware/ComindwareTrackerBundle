<?php
/**
 * A Symfony bundle for working with Comindware Tracker.
 *
 * @copyright 2016, Comindware, http://comindware.com/
 * @license   http://opensource.org/licenses/MIT MIT
 */
namespace Comindware\ComindwareTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     *
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('comindware_tracker');

        $this->addConnectionsNode($root);

        $root->end();

        return $treeBuilder;
    }

    /**
     * Add "connections" node.
     *
     * @param ArrayNodeDefinition $root
     */
    private function addConnectionsNode(ArrayNodeDefinition $root)
    {
        $root->fixXmlConfig('connection');
        $children = $root->children();
        $connections = $children->arrayNode('connections');
        $connections->useAttributeAsKey('name');
        /** @var ArrayNodeDefinition $prototype */
        $prototype = $connections->prototype('array');

        $this->addConnectionNode($prototype);

        $prototype->end();
        $connections->end();
        $children->end();
    }

    /**
     * Add single connection node.
     *
     * @param ArrayNodeDefinition $root
     */
    private function addConnectionNode(ArrayNodeDefinition $root)
    {
        $connection = $root->children();

        $connection->scalarNode('url')->isRequired()->end();
        $connection->scalarNode('token')->isRequired()->end();

        $http = $connection->arrayNode('http');
        $params = $http->children();
        $params->scalarNode('client')->end();
        $params->scalarNode('message_factory')->end();
        $params->scalarNode('stream_factory')->end();
        $params->end();
        $http->end();

        $logging = $connection->arrayNode('logging');
        $params = $logging->children();
        $params->scalarNode('service')->end();
        $params->end();
        $logging->end();

        $connection->end();
    }
}
