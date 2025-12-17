<?php

namespace Tekquora\Watermark\Events;

class RegisterAdminMenu
{
    public array &$menu;

    public function __construct(array &$menu)
    {
        $this->menu = &$menu;
    }
}
