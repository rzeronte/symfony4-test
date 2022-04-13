<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class ApiCommandPage
{
    protected MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param object|\Symfony\Component\Messenger\Envelope $message
     * @throws \Throwable
     */
    protected function dispatch($message): Envelope
    {
        try {
            return $this->commandBus->dispatch($message);
        } catch (HandlerFailedException $e) {
            while ($e instanceof \App\Shared\Infrastructure\Delivery\Rest\HandlerFailedException) {
                $e = $e->getPrevious();
                \assert($e instanceof Throwable);
            }

            throw $e;
        }
    }
}
