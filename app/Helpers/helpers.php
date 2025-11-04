<?php

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        $setting = \App\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
