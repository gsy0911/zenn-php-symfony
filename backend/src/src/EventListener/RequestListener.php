<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Service\UserIdService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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

        // APIリクエエスト時
        if (strpos($event->getRequest()->getRequestUri(), '/api/') === 0) {
            $this->userIdService->setUserId(2);
        }
    }
}
