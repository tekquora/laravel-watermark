<?php

namespace Tekquora\Watermark\Support;

class LayoutResolver
{
    public static function resolve(): string
    {
        $layout = config('watermark.views.layout');

        // 1️⃣ Callable layout (Botble / dynamic systems)
        if (is_callable($layout)) {
            return call_user_func($layout);
        }

        // 2️⃣ Explicit layout string
        if (is_string($layout) && view()->exists($layout)) {
            return $layout;
        }

        // 3️⃣ Botble CMS auto-detect
        if (class_exists(\BaseHelper::class)) {
            return \BaseHelper::getAdminMasterLayoutTemplate();
        }

        // 4️⃣ Laravel Breeze / Jetstream
        if (view()->exists('layouts.admin')) {
            return 'layouts.admin';
        }

        // 5️⃣ Default fallback
        return 'layouts.app';
    }
}
