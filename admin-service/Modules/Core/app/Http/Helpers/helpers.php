<?php

use Modules\Core\Core;
use Modules\Core\SystemConfig;

if (! function_exists('core')) {
    /**
     * Core helper.
     */
    function core(): Core
    {
        return app('core');
    }
}


if (! function_exists('system_config')) {
    /**
     * System Config helper.
     */
    function system_config(): SystemConfig
    {
        return app('system_config');
    }
}


if (! function_exists('clean_path')) {
    /**
     * Clean path.
    */
    function clean_path(string $path): string
    {
        return collect(explode('/', $path))
            ->filter(fn ($segment) => ! empty($segment))
            ->join('/');
    }
}

?>