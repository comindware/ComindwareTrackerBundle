<?php
/**
 * A Symfony bundle for working with Comindware Tracker.
 *
 * @copyright 2016, Comindware, http://comindware.com/
 * @license   http://opensource.org/licenses/MIT MIT
 */
namespace Comindware\ComindwareTrackerBundle\DependencyInjection;

use Comindware\ComindwareTrackerBundle\DependencyInjection\Factory\ConnectionFactory;
use Comindware\Tracker\API\Api;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Container extension.
 */
class ComindwareTrackerExtension extends ConfigurableExtension
{
    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'comindware_tracker';
    }

    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        if (array_key_exists('connections', $mergedConfig)) {
            $this->createConnections($container, $mergedConfig['connections']);
        }
    }

    /**
     * Create connection service.
     *
     * @param ContainerBuilder $container
     * @param array            $configs
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function createConnections(ContainerBuilder $container, array $configs)
    {
        foreach ($configs as $name => $config) {
            if (!array_key_exists('http', $config) || !$config['http']) {
                $config['http'] = [];
            }

            $url = $config['url'];
            $token = $config['token'];
            $httpClient = null;
            $messageFactory = null;
            $streamFactory = null;

            if (array_key_exists('client', $config['http'])) {
                $httpClient = new Reference($config['http']['client']);
            }

            if (array_key_exists('message_factory', $config['http'])) {
                $messageFactory = new Reference($config['http']['message_factory']);
            }

            if (array_key_exists('stream_factory', $config['http'])) {
                $streamFactory = new Reference($config['http']['stream_factory']);
            }

            $service = new Definition(
                Api::class,
                [$url, $token, $httpClient, $messageFactory, $streamFactory]
            );
            $service->setFactory([ConnectionFactory::class, 'create']);

            if (array_key_exists('logging', $config)) {
                if (array_key_exists('service', $config['logger'])) {
                    $logger = new Reference($config['logging']['service']);
                    $service->addMethodCall('setLogger', [$logger]);
                }
            }

            $container->setDefinition('comindware.tracker.' . $name, $service);
        }
    }
}
