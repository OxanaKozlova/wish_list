<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class BeforeActionSubscriber implements EventSubscriberInterface
{
    private const JSON_PATTERN = '/json/';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'convertJsonToArray',
        ];
    }

    public function convertJsonToArray(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->clientSendsJson($request) || !$request->getContent()) {
            return;
        }

        $data = json_decode((string) $request->getContent(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('invalid json body: '.json_last_error_msg());
        }

        $request->request->replace(is_array($data) ? $data : []);
    }

    private function clientSendsJson(Request $request): bool
    {
        $header = (string) $request->getContentType();
        if (preg_match(self::JSON_PATTERN, $header)) {
            return true;
        }

        return false;
    }
}
