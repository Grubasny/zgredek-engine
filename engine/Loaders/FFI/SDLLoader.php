<?php

namespace ZgredekEngine\Loaders\FFI;

use FFI;
use ZgredekEngine\Lib\SDL2Interface;

class SDLLoader extends AbstractLoader {
    public const REQUIRED_TYPES = [
        'struct SDL_Window SDL_Window',
        'struct SDL_Renderer SDL_Renderer',
        'struct SDL_Texture SDL_Texture',
        'struct { int x, y, w, h; } SDL_Rect',
        'struct SDL_Keysym { int32_t scancode; int32_t sym; uint16_t mod; uint32_t unused; } SDL_Keysym',
        'struct SDL_KeyboardEvent { uint32_t type; uint32_t timestamp; uint32_t windowID; uint8_t state; uint8_t repeat; uint8_t padding2; uint8_t padding3; SDL_Keysym keysym; } SDL_KeyboardEvent',
        'union SDL_Event { uint32_t type; SDL_KeyboardEvent key; uint8_t padding[56]; } SDL_Event',
    ];

    public const REQUIRED_FUNCTIONS = [
        'int SDL_Init(uint32_t flags)',
        'SDL_Window* SDL_CreateWindow(const char* title, int x, int y, int w, int h, uint32_t flags)',
        'SDL_Renderer* SDL_CreateRenderer(SDL_Window* window, int index, uint32_t flags)',
        'int SDL_PollEvent(SDL_Event* event)',
        'int SDL_RenderSetLogicalSize(SDL_Renderer* renderer, int w, int h)',
        'void SDL_RenderClear(SDL_Renderer* renderer)',
        'int SDL_RenderCopy(SDL_Renderer* r, SDL_Texture* t, const SDL_Rect* s, const SDL_Rect* d)',
        'void SDL_RenderPresent(SDL_Renderer* renderer)',
        'void SDL_DestroyRenderer(SDL_Renderer* renderer)',
        'void SDL_DestroyWindow(SDL_Window* window)',
        'void SDL_Quit(void)',
        'void SDL_FreeSurface(void* surface)',
        'SDL_Texture* SDL_CreateTextureFromSurface(SDL_Renderer* renderer, void* surface)',
        'const char* SDL_GetError(void)',   
        'uint32_t SDL_GetTicks(void)',
        'uint64_t SDL_GetTicks64(void)',
    ];

    protected function getLibraryName(): string
    {
        return SDL2Interface::LIBRARY_NAME;
    }

    protected function getRequiredTypes(): array
    {
        return self::REQUIRED_TYPES;
    }

    protected function getRequiredFunctions(): array
    {
        return self::REQUIRED_FUNCTIONS;
    }
}