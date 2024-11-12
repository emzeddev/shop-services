<?php

use Modules\User\Bouncer;

if (! function_exists('bouncer')) {
    function bouncer(): Bouncer
    {
        return app()->make(Bouncer::class);
    }
}