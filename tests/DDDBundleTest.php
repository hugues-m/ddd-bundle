<?php

namespace HMLB\DDDBundle\Tests;

use HMLB\DDDBundle\Tests\Functional\TestKernel;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DDDBundleTest.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class DDDBundleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $this->container = $kernel->getContainer();
    }

    /**
     * @test
     *
     * @dataProvider expectedInitialParameters
     */
    public function parametersAreInitialized($parameterName, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->container->getParameter($parameterName));
    }

    /**
     * Provider for parametersAreInitialized().
     *
     * @return array
     */
    public static function expectedInitialParameters()
    {
        return [
            [
                'hmlb_ddd.persistence.persist_commands',
                true,
            ],
            [
                'hmlb_ddd.persistence.persist_events',
                true,
            ],
            [
                'hmlb_ddd.persistence.event_repository_service',
                'hmlb_ddd.repository.event.default',
            ],
            [
                'hmlb_ddd.persistence.command_repository_service',
                'hmlb_ddd.repository.command.default',
            ],
            [
                'hmlb_ddd.db_driver',
                'orm',
            ],
        ];
    }
}
