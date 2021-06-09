<?php

namespace App\Service;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;

class BannerUpdate
{
    public function update(Request $request, Event $event, string $class): void
    {
        $requestProp = $request->request->get('prop');
        $requestValue = $request->request->get('value');

        $requestProp = htmlentities(trim($requestProp));
        $requestValue = htmlentities(trim($requestValue));

        $eventMethods = get_class_methods($class);
        foreach ($eventMethods as $method) {
            if ($method == 'set' . ucfirst($requestProp)) {
                $event->$method($requestValue);
                break;
            }
        }
    }
}
