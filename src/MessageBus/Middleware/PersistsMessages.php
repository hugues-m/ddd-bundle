<?php

namespace HMLB\DDDBundle\MessageBus\Middleware;

use Doctrine\Common\Persistence\ObjectManager;
use HMLB\DDD\Exception\Exception;
use HMLB\DDD\Message\Command\Command;
use HMLB\DDD\Message\Event\Event;
use HMLB\DDD\Message\Message;
use HMLB\DDDBundle\Persistence\PersistentMessage;
use Psr\Log\LoggerInterface;
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
    public function __construct(LoggerInterface $logger, ObjectManager $om, bool $persistCommands, bool $persistEvents)
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
        if ($message instanceof PersistentMessage && $this->shouldPersistMessage($message)) {
            try {
                $message->getId();
            } catch (Exception $e) {
                $message->initializeId();
            }

            $this->om->persist($message);
        }

        $next($message);
    }

    /**
     * @param PersistentMessage $message
     *
     * @return bool
     */
    private function shouldPersistMessage(PersistentMessage $message): bool
    {
        return ($message instanceof Command && $this->persistCommands) ||
        ($message instanceof Event && $this->persistEvents);
    }
}
