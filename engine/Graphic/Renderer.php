<?php

namespace ZgredekEngine\Graphic;

use FFI;
use ZgredekEngine\State\TextureState;
use ZgredekEngine\Exceptions\SDLNewException;
use ZgredekEngine\Exceptions\SDLWindowNotInitializedException;
use ZgredekEngine\Lib\Libraries;
use ZgredekEngine\Lib\SDL2Interface;
use ZgredekEngine\State\CharacterState;
use ZgredekEngine\Zgredek\WindowManager;

class Renderer {
    private $sdl;

    /** @var FFI\CData $rectSource */
    private $rectSource;

    /** @var FFI\CData $rectDestination */
    private $rectDestination;

    private $rectSourceAddr;
    private $rectDestinationAddr;

    /**
     * @param SDL2Interface $sdl
     * @param WindowManager $sdlRenderer
     */
    public function __construct($sdl, private WindowManager $windowManager) {
        $this->sdl = $sdl;

        $this->rectSource = $this->sdl->new('SDL_Rect') 
            ?? throw new SDLNewException('SDL_Rect', $sdl);

        $this->rectDestination = $this->sdl->new('SDL_Rect') 
            ?? throw new SDLNewException('SDL_Rect', $sdl);

        $this->rectSourceAddr = FFI::addr($this->rectSource);
        $this->rectDestinationAddr = FFI::addr($this->rectDestination);
    }

    public function clear(): void 
    {
        $this->sdl->SDL_RenderClear(
            $this->windowManager->sdlRenderer ?? throw new SDLWindowNotInitializedException($this->sdl)
        );
    }

    public function present(): void 
    {
        $this->sdl->SDL_RenderPresent(
            $this->windowManager->sdlRenderer ?? throw new SDLWindowNotInitializedException($this->sdl)
        );
    }

    public function drawCharacters(Libraries $libraries, CharacterState $characterState, TextureState $textureState): void 
    {
        $sdlRenderer = $this->windowManager->sdlRenderer ?? throw new SDLWindowNotInitializedException($this->sdl);

        $active = $characterState->active;
        $xArr = $characterState->x;
        $yArr = $characterState->y;
        $direction = $characterState->direction;
        $currentFrame = $characterState->currentFrame;

        $textures = $textureState->textures;
        $characterTextures = $textureState->characterTextures;
        $directionOffset = $textureState->directionOffset;
        $rectX = $textureState->rectX;
        $rectY = $textureState->rectY;
        $rectW = $textureState->rectW;
        $rectH = $textureState->rectH;

        /**
         * @todo get data from Window after create WindowManager
         */
        $screenWidth = 1280;
        $screenHeight = 720;

        $rectSource = $this->rectSource;
        $rectDestination = $this->rectDestination;
        $rectSourceAddr = $this->rectSourceAddr;
        $rectDestinationAddr = $this->rectDestinationAddr;

        foreach ($active as $id => $isActive) {
            $bitKey = ($id << 4) | ($direction[$id] & 0xF);

            if (!$isActive || !isset($characterTextures[$bitKey]) || $characterTextures[$bitKey] === -1) {
                continue;
            }

            $textureId = $characterTextures[$bitKey];
            $texture = $textures[$textureId] ?? null;
            
            if (!$texture) {
                continue;
            }

            $bitKeyFrame = $directionOffset[$bitKey] + $currentFrame[$id]; 

            print "CURRENT FRAME: {$currentFrame[$id]}\n\n";
            $dx = (int)$xArr[$id];
            $dy = (int)$yArr[$id];
            $w = $rectW[$bitKeyFrame];
            $h = $rectH[$bitKeyFrame];

            if ($dx + $w < 0 || $dx > $screenWidth || 
                $dy + $h < 0 || $dy > $screenHeight) {
                continue;
            }

            $rectSource->x = $rectX[$bitKeyFrame];
            $rectSource->y = $rectY[$bitKeyFrame];
            $rectSource->w = $w;
            $rectSource->h = $h;

            $rectDestination->x = $dx;
            $rectDestination->y = $dy;
            $rectDestination->w = $w;
            $rectDestination->h = $h;

            $this->sdl->SDL_RenderCopy(
                $sdlRenderer, 
                $texture, 
                $rectSourceAddr, 
                $rectDestinationAddr
            );
        }
    }
}