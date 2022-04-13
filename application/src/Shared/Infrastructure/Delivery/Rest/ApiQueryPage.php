<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

class ApiQueryPage
{
    protected MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param  object|Envelope $message
     * @return mixed
     * @throws Throwable
     */
    protected function ask($message)
    {
        try {
            $envelop = $this->queryBus->dispatch($message);

            $handledStamp = $envelop->last(HandledStamp::class);
            \assert($handledStamp instanceof HandledStamp);

            return $handledStamp->getResult();
        } catch (HandlerFailedException $e) {
            throw $this->raiseException($e);
        }
    }

    protected function raiseException(Throwable $e): Throwable
    {
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
            \assert($e instanceof Throwable);
        }

        return $e;
    }
}
