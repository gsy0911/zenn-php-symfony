<?php

namespace App\EventListener;

use App\Service\UserIdService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;


#[AsEventListener(event: ResponseEvent::class, method: 'onKernelResponse')]
readonly class ResponseListener
{
    public function __construct(
        private UserIdService $userIdService,
        private LoggerInterface $logger,
    )
    {

    }
    public function onKernelResponse(ResponseEvent $event): void
    {
        $this->logger->info($this->userIdService->getUserId());
        $this->logger->info("hello, on ResponseListener");
    }
}
