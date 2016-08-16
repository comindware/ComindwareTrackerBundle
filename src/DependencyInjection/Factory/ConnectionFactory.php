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
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;

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
     * @param StreamFactory       $streamFactory  HTTP stream factory.
     *
     * @return Api
     *
     * @throws \Http\Discovery\Exception\NotFoundException
     */
    public static function create(
        $baseUri,
        $token,
        HttpClient $httpClient = null,
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    ) {
        if (null === $httpClient) {
            $httpClient = HttpClientDiscovery::find();
        }
        if (null === $messageFactory) {
            $messageFactory = MessageFactoryDiscovery::find();
        }
        if (null === $streamFactory) {
            $streamFactory = StreamFactoryDiscovery::find();
        }
        $client = new Client($baseUri, $token, $httpClient, $messageFactory, $streamFactory);

        return new Api($client);
    }
}
