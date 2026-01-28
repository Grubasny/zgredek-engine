<?php

namespace ZgredekEngine\Exceptions;

class TextureNotRegisteredException extends \Exception
{
    public const CODE = 2001;
    
    public function __construct(string $textureName = "")
    {
        $message = "Zgredek does not recognize texture: {$textureName}. Try to use registerTexture() first.";

        return parent::__construct($message, self::CODE);
    }
}