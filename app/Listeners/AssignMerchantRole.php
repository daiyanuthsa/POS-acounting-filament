<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AssignMerchantRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        $merchantRole = Role::findByName('merchant');

        if ($merchantRole) {
            $user->assignRole($merchantRole);
        }
    }
}
