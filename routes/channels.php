<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('volunteer.notifications.{id}', function ($user, $id) {
    return auth('volunteer')->check() && auth('volunteer')->user()->id == $id;
});

Broadcast::channel('organization.notifications.{id}', function ($user, $id) {
    return auth('organization')->check() && auth('organization')->user()->id == $id;
});
