<?php

use Flarum\Event\UserWasRegistered;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->listen(UserWasRegistered::class, function (UserWasRegistered $event) {
        $event->user->activate();
        $event->user->save();
    });
};