<?php

namespace ZgredekEngine\Exceptions;

class SDLImgLoadException extends SDLException
{
    public const CODE = 1003;

    public function __construct(string $path, $sdl)
    {
        $message = "Zgredek is not able to load {$path}.";

        return parent::__construct($message, $sdl, self::CODE);
    }
}