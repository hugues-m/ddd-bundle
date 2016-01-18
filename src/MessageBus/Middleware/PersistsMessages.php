<?php

namespace HMLB\DDDBundle\MessageBus\Middleware;

use Doctrine\Common\Persistence\ObjectManager;
use HMLB\DDD\Entity\Identity;
use HMLB\DDD\Message\Message;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

/**
 * Adds messages to object manager persistence.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class PersistsMessages implements MessageBusMiddleware
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $persistCommands;

    /**
     * @var bool
     */
    private $persistEvents;

    /**
     * PersistsMessages constructor.
     *
     * @param LoggerInterface $logger
     * @param ObjectManager   $om
     * @param bool            $persistCommands
     * @param bool            $persistEvents
     */
    public function __construct(LoggerInterface $logger, ObjectManager $om, $persistCommands, $persistEvents)
    {
        $this->logger = $logger;
        $this->om = $om;
        $this->persistCommands = $persistCommands;
        $this->persistEvents = $persistEvents;
    }

    /**
     * @param Message  $message
     * @param callable $next
     */
    public function handle($message, callable $next)
    {
        $messageClass = get_class($message);
        if ($this->om->getMetadataFactory()->isTransient($messageClass)) {
            $next($message);
            $this->logger->warning(
                sprintf('No OM Metadata for message %s', $message->messageName())
            );

            return;
        }

        $reflection = new ReflectionClass(get_class($message));
        $id = $reflection->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($message, new Identity());
        $id->setAccessible(false);

        $this->logger->debug(
            sprintf('PersistsMessages persists message '.$message->getId())
        );
        $this->om->persist($message);
        $next($message);
    }
}
