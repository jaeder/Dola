<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return DFZ\Dola\Facades\Dola::setting($key, $default);
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return DFZ\Dola\Models\Menu::display($menuName, $type, $options);
    }
}

if (!function_exists('dola_asset')) {
    function dola_asset($path, $secure = null)
    {
        return asset(config('dola.assets_path').'/'.$path, $secure);
    }
}
