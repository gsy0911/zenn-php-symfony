<?php
namespace App\EventListener;

use App\Service\UserIdService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class, method: 'onKernelRequest')]
final readonly class RequestListener
{
    public function __construct(
        private UserIdService $userIdService,
    )
    {

    }
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        $request = $event->getRequest();
        $this->userIdService->setUserId(1);
    }
}
