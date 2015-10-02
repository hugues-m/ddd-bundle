<?php

namespace HMLB\DDDBundle\MessageBus\Middleware;

use Doctrine\Common\Persistence\ObjectManager;
use HMLB\DDD\Message\Command\Command;
use HMLB\DDD\Message\Message;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

/**
 * Commits Object Manager transaction for persisted domain models.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class CommitsObjectManagerTransaction implements MessageBusMiddleware
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, ObjectManager $om)
    {
        $this->logger = $logger;
        $this->om = $om;
    }

    /**
     * @param Message  $message
     * @param callable $next
     */
    public function handle($message, callable $next)
    {
        $this->logger->debug('CommitsObjectManagerTransaction handles '.$message::messageName());
        $next($message);
        $this->logger->debug('CommitsObjectManagerTransaction is handling '.$message::messageName());

        if ($message instanceof Command) {
            $this->logger->debug('CommitsObjectManagerTransaction flushes Object manager');
            $this->om->flush();
        }
        $this->logger->debug('CommitsObjectManagerTransaction finished handling '.$message::messageName());
    }
}
