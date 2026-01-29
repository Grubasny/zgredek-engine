<?php

namespace ZgredekEngine\Zgredek;

use ZgredekEngine\Exceptions\SDLException;
use ZgredekEngine\Lib\SDL2ImageInterface;
use ZgredekEngine\Exceptions\SDLInitException;
use ZgredekEngine\Graphic\Renderer;
use ZgredekEngine\Input\GameController;
use ZgredekEngine\Lib\Libraries;
use ZgredekEngine\Lib\SDL2Interface;

class WindowManager {
    public const SDL_WINDOW_SHOWN = 0x00000004;
    public const SDL_WINDOW_FULLSCREEN_DESKTOP = 0x00001001;

    public const SDL_WINDOWPOS_CENTERED_MASK = 0x2FFF0000;

    public const SDL_RENDERER_SOFTWARE = 0x00000001;
    public const SDL_RENDERER_ACCELERATED = 0x00000002;
    public const SDL_RENDERER_PRESENTVSYNC = 0x00000004;

    public const DEFAULT_WINDOW_WIDTH = 1280;
    public const DEFAULT_WINDOW_HEIGHT = 720;

    public $window = null;
    public $sdlRenderer = null;

    public function __construct(
        public Libraries $libraries,
        public Renderer $renderer,
        public GameController $gameController
    ) {}

    public function init(
        string $title, 
        int $width = self::DEFAULT_WINDOW_WIDTH, 
        int $height = self::DEFAULT_WINDOW_HEIGHT, 
        bool $fullscreen = false
    ): array {
        if ($this->sdlRenderer !== null) {
            return [$this->window, $this->sdlRenderer];
        }

        $sdl = $this->libraries->sdl;
        if ($sdl->SDL_Init(0x00000020 | 0x00004000) < 0) {
            throw new SDLInitException($sdl);
        }

        $flags = self::SDL_WINDOW_SHOWN;
        if ($fullscreen) {
            $flags |= self::SDL_WINDOW_FULLSCREEN_DESKTOP;
        }

        $this->window = $sdl->SDL_CreateWindow(
            $title, 
            self::SDL_WINDOWPOS_CENTERED_MASK, 
            self::SDL_WINDOWPOS_CENTERED_MASK, 
            $width, $height, 
            $flags
        );

        if ($this->window === null) {
            throw new SDLException('SDL_CreateWindow returns NULL', $sdl);
        }

        $this->sdlRenderer = $sdl->SDL_CreateRenderer(
            $this->window, 
            -1, 
            self::SDL_RENDERER_SOFTWARE //self::SDL_RENDERER_ACCELERATED | self::SDL_RENDERER_PRESENTVSYNC
        );

        if ($this->sdlRenderer === null) {
            throw new SDLException('SDL_CreateRenderer returns NULL', $sdl);
        }
        
        $sdl->SDL_RenderSetLogicalSize($this->sdlRenderer, $width, $height);

        return [$this->window, $this->sdlRenderer];
    }

    public function __destruct() {
        $sdl = $this->libraries->sdl;

        $sdl->SDL_DestroyRenderer($this->sdlRenderer);
        $sdl->SDL_DestroyWindow($this->window);
        $sdl->SDL_Quit();

        print PHP_EOL . PHP_EOL . PHP_EOL 
            . "\t    Bye!" . PHP_EOL 
            . "\t  Bye! Bye!" . PHP_EOL 
            . "\tBye! Bye! Bye!" . PHP_EOL 
            . "\t  Bye! Bye!" . PHP_EOL 
            . "\t    Bye!" . PHP_EOL 
            . PHP_EOL. PHP_EOL;
    }
}