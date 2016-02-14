<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\MessageBus\Middleware;

use HMLB\DDD\Exception\Exception;
use HMLB\DDDBundle\EventListener\CollectsEventsFromEntities;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

/**
 * This subscribers throws an exception if DomainEvents have been collected and will not be notified.
 *
 * It should have High priority to check for Events after all Notifiers like "NotifiesDomainEvents" have been called.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class MonitorsDomainEvents implements MessageBusMiddleware
{
    /**
     * @var CollectsEventsFromEntities
     */
    private $collectsEventsFromEntities;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface            $logger
     * @param CollectsEventsFromEntities $collectsEventsFromEntities
     */
    public function __construct(
        LoggerInterface $logger,
        CollectsEventsFromEntities $collectsEventsFromEntities
    ) {
        $this->logger = $logger;
        $this->collectsEventsFromEntities = $collectsEventsFromEntities;
    }

    /**
     * @param object   $message
     * @param callable $next
     *
     * @throws Exception
     */
    public function handle($message, callable $next)
    {
        $next($message);
        $events = $this->collectsEventsFromEntities->recordedMessages();
        $count = count($events);
        $this->logger->debug('MonitorsDomainEvents saw '.$count.' events');

        if ($count) {
            foreach ($events as $event) {
                $this->logger->debug('MonitorsDomainEvents saw '.get_class($event));
            }
            if ($count == 1) {
                $message = sprintf(
                    '%s Domain Event has been collected but will not be notified.
                    You should not raise domain event during the handling of a Event message but only for a Command.',
                    get_class(array_pop($events))
                );
            } else {
                $message = sprintf(
                    '%s Domain Event(s) have been collected but will not be notified.
                    You should not raise domain event during the handling of a Event message but only for a Command.',
                    $count
                );
            }
            throw new Exception($message);
        }
    }
}
