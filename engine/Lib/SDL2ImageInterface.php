<?php

namespace ZgredekEngine\Lib;

/**
 * @method int  IMG_Init(int $flags)
 * @method void IMG_Load(string $file)
 * @method void IMG_Quit()
 *
 * @method static ?FFI\CData new($type, bool $owned = true, bool $persistent = false)
 * @method static FFI\CData addr(CData $cdata)
 */
interface SDL2ImageInterface
{
    public const LIBRARY_NAME = 'libSDL2_image-2.0.so.0';
}