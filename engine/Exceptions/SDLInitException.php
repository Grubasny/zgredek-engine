<?php

namespace ZgredekEngine\Exceptions;

class SDLInitException extends SDLException
{
    public const CODE = 1001;

    public function __construct($sdl)
    {
        return parent::__construct('SDL Init Exception.', $sdl, self::CODE);
    }
}