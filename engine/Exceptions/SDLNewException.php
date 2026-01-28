<?php

namespace ZgredekEngine\Exceptions;

class SDLNewException extends SDLException
{
    public const CODE = 1002;

    public function __construct(string $name, $sdl)
    {
        $message = "Zgredek is not able to create {$name}.";

        return parent::__construct($message, $sdl, self::CODE);
    }
}