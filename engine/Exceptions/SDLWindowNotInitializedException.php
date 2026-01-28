<?php

namespace ZgredekEngine\Exceptions;

class SDLWindowNotInitializedException extends SDLException
{
    public const CODE = 1004;

    public function __construct($sdl)
    {
        $message = 'Zgredek has no sdlRenderer - please call WindowManager::init first.';

        return parent::__construct($message, $sdl, self::CODE);
    }
}