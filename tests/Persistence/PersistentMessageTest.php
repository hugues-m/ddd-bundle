<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Tests\Persistence;

use HMLB\DDD\Entity\Identity;
use HMLB\DDDBundle\Repository\PersistentCommandRepository;
use HMLB\DDDBundle\Repository\PersistentEventRepository;
use HMLB\DDDBundle\Tests\Functional\TestKernel;
use HMLB\DDDBundle\Tests\Message\DoSomethingImportant;
use HMLB\DDDBundle\Tests\Message\SomethingImportantHappened;
use PHPUnit_Framework_TestCase;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * PersistentMessageTest.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class PersistentMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var TestKernel
     */
    private $kernel;

    public function setUp()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();

        $this->executeCommand('doctrine:database:create');
        $this->executeCommand('doctrine:schema:update', ['--force' => true]);
    }

    public function tearDown()
    {
        $this->executeCommand('doctrine:database:drop', ['--force' => true]);
    }

    protected function executeCommand($command, array $options = [])
    {
        $args = [
            'command' => $command,
        ];
        //$args['--env'] = 'test';
        //$args['--quiet'] = true;
        $args = array_merge($args, $options);

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $application->run(new ArrayInput($args));
    }

    /**
     * @test
     */
    public function commandsCanBePersisted()
    {
        $command = new DoSomethingImportant('Brush teeth');

        $this->getCommandBus()->handle($command);

        $this->assertInstanceOf(Identity::class, $command->getId());

        /* @var PersistentCommandRepository $repository */
        $commandRepository = $this->container->get('hmlb_ddd.repository.command');

        $foundCommand = $commandRepository->get($command->getId());
        $this->assertSame($command, $foundCommand);

        $foundCommands = $commandRepository->getByMessage(DoSomethingImportant::class);
        $this->assertCount(1, $foundCommands);
        $this->assertSame($command, $foundCommands[0]);

        /** @var PersistentEventRepository $eventRepository */
        $eventRepository = $this->container->get('hmlb_ddd.repository.event');

        $foundEvents = $eventRepository->getByMessage(SomethingImportantHappened::class);
        $this->assertCount(1, $foundEvents);

        /** @var SomethingImportantHappened $event */
        $event = $foundEvents[0];
        $this->assertInstanceOf(SomethingImportantHappened::class, $event);
        $this->assertEquals($command->getTask(), $event->getThing());

        $foundEvent = $eventRepository->get($event->getId());
        $this->assertSame($event, $foundEvent);
    }

    /**
     * @return MessageBus
     */
    private function getCommandBus(): MessageBus
    {
        return $this->container->get('command_bus');
    }
}
