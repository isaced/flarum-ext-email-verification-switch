<?php

use Flarum\Extend;
use Flarum\User\Event\Registered;

return [
    (new Extend\Event)
        ->listen(Registered::class, function (Registered $event) {
            $event->user->activate();
            $event->user->save();
        })
];
