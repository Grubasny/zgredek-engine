<?php

namespace ZgredekEngine\Exceptions;

use ZgredekEngine\Lib\SDL2Interface;

class SDLException extends \Exception
{
    public const CODE = 1000;

    /**
     * @param string $message
     * @param SDL2Interface $sdl
     */
    public function __construct(string $message, $sdl, $code = self::CODE)
    {
        $message .= ' Error: ' . $sdl->SDL_GetError();

        return parent::__construct($message, $code);
    }
}