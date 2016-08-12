<?php
/**
 * A Symfony bundle for working with Comindware Tracker.
 *
 * @copyright 2016, Comindware, http://comindware.com/
 * @license   http://opensource.org/licenses/MIT MIT
 */
namespace Comindware\ComindwareTrackerBundle\Tests\DependencyInjection\Factory;

use Comindware\ComindwareTrackerBundle\DependencyInjection\Factory\ConnectionFactory;
use Comindware\Tracker\API\Api;

/**
 * Tests for Comindware\ComindwareTrackerBundle\DependencyInjection\Factory\ConnectionFactory
 *
 * @covers Comindware\ComindwareTrackerBundle\DependencyInjection\Factory\ConnectionFactory
 */
class ConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAutodetectAll()
    {
        $connection = ConnectionFactory::create('http://example.com/', 'my.token');
        static::assertInstanceOf(Api::class, $connection);
    }
}
