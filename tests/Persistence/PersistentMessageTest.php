<?php
namespace HMLB\DDDBundle\Tests;

use HMLB\DDD\Entity\Identity;
use HMLB\DDDBundle\Tests\Functional\TestKernel;
use HMLB\DDDBundle\Tests\Message\DoSomethingImportant;
use PHPUnit_Framework_TestCase;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * PersistentMessageTest
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
     *
     */
    public function commandsCanBePersisted()
    {

        $command = new DoSomethingImportant('Brush teeth');

        $this->getCommandBus()->handle($command);

        $this->assertInstanceOf(Identity::class, $command->getId());
    }

    /**
     *
     * @return MessageBus
     *
     */
    private function getCommandBus(): MessageBus
    {
        return $this->container->get('command_bus');
    }

}
