<?php

use Flarum\User\Event\Registered;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->listen(Registered::class, function (Registered $event) {
        $event->user->activate();
        $event->user->save();
    });
};
