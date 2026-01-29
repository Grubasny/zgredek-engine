<?php

namespace ZgredekEngine\Lib;

use FFI\CData;

/**
 * @method int    SDL_Init(int $flags)
 * @method object SDL_CreateWindow(string $title, int $x, int $y, int $w, int $h, int $flags)
 * @method object SDL_CreateRenderer(object $window, int $index, int $flags)
 * @method int    SDL_PollEvent(object $event)
 * @method void   SDL_RenderClear(object $renderer)
 * @method int    SDL_RenderCopy(object $r, object $t, object $s, object $d)
 * @method void   SDL_RenderPresent(object $renderer)
 * @method void   SDL_DestroyRenderer(object $renderer)
 * @method void   SDL_DestroyWindow(object $window)
 * @method void   SDL_Quit()
 * @method void   SDL_FreeSurface(object $surface)
 * @method object SDL_CreateTextureFromSurface(object $renderer, object $surface)
 * @method string SDL_GetError()
 * @method int    SDL_RenderSetLogicalSize(object $renderer, int $w, int $h)
 * @method int    SDL_GetTicks() 
 * @method int    SDL_GetTicks64() 
 */
interface SDL2Interface
{
    public const LIBRARY_NAME = 'libSDL2-2.0.so.0';

    public const SDL_INIT_TIMER = 0x00000001;
    public const SDL_INIT_AUDIO = 0x00000010;
    public const SDL_INIT_VIDEO = 0x00000020;
    public const SDL_INIT_JOYSTICK = 0x00000200;
    public const SDL_INIT_HAPTIC = 0x00001000;
    public const SDL_INIT_GAMECONTROLLER = 0x00002000;
    public const SDL_INIT_EVENTS = 0x00004000;
    public const SDL_INIT_EVERYTHING = 0x0000FFFF;
}