<?php

namespace ZgredekEngine\Loaders\FFI;

use FFI;
use ZgredekEngine\Lib\SDL2ImageInterface;

class SDLImageLoader extends AbstractLoader {
    public const REQUIRED_FUNCTIONS = [
        'int IMG_Init(int flags)',
        'void* IMG_Load(const char *file)',
        'void IMG_Quit(void)',
    ];

    protected function getLibraryName(): string
    {
        return SDL2ImageInterface::LIBRARY_NAME;
    }

    protected function getRequiredTypes(): array
    {
        return [];
    }

    protected function getRequiredFunctions(): array
    {
        return self::REQUIRED_FUNCTIONS;
    }
}