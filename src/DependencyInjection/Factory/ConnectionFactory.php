<?php
/**
 * A Symfony bundle for working with Comindware Tracker.
 *
 * @copyright 2016, Comindware, http://comindware.com/
 * @license   http://opensource.org/licenses/MIT MIT
 */
namespace Comindware\ComindwareTrackerBundle\DependencyInjection\Factory;

use Comindware\Tracker\API\Api;
use Comindware\Tracker\API\Client;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;

/**
 * Connection factory.
 */
class ConnectionFactory
{
    /**
     * Create new Api instance.
     *
     * @param string              $baseUri        Comindware Tracker root URI.
     * @param string              $token          Authentication token.
     * @param HttpClient|null     $httpClient     HTTP client.
     * @param MessageFactory|null $messageFactory HTTP message factory.
     *
     * @return Api
     *
     * @throws \Http\Discovery\Exception\NotFoundException
     */
    public static function create($baseUri, $token, $httpClient = null, $messageFactory = null)
    {
        if (null === $httpClient) {
            $httpClient = HttpClientDiscovery::find();
        }
        if (null === $messageFactory) {
            $messageFactory = MessageFactoryDiscovery::find();
        }
        $client = new Client($baseUri, $token, $httpClient, $messageFactory);

        return new Api($client);
    }
}
