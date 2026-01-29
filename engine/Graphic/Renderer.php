<?php

namespace ZgredekEngine\Graphic;

use DateTime;
use FFI;
use ZgredekEngine\Exceptions\SDLNewException;
use ZgredekEngine\Lib\Libraries;
use ZgredekEngine\Lib\SDL2Interface;
use ZgredekEngine\State\States;
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
    public function __construct(Libraries $libraries) {
        $sdl = $libraries->sdl;
        $this->sdl = $sdl;

        $this->rectSource = $sdl->new('SDL_Rect') 
            ?? throw new SDLNewException('SDL_Rect', $sdl);

        $this->rectDestination = $sdl->new('SDL_Rect') 
            ?? throw new SDLNewException('SDL_Rect', $sdl);

        $this->rectSourceAddr = FFI::addr($this->rectSource);
        $this->rectDestinationAddr = FFI::addr($this->rectDestination);
    }

    public function clear($sdlRenderer): void 
    {
        $this->sdl->SDL_RenderClear($sdlRenderer);
    }

    public function present($sdlRenderer): void 
    {
        $this->sdl->SDL_RenderPresent($sdlRenderer);
    }

    public function drawCharacters($sdlRenderer, States $states): void 
    {
        $characterState = $states->characterState;
        $textureState = $states->textureState;

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
            $currentDirection = $direction[$id];
            $characterBitKey = ($id << 4) | ($currentDirection & 0xF);

            if (!$isActive || !isset($characterTextures[$characterBitKey]) || $characterTextures[$characterBitKey] === -1) {
                continue;
            }

            $textureId = $characterTextures[$characterBitKey];
            $texture = $textures[$textureId] ?? null;
            
            if (!$texture) {
                continue;
            }

            $textureBitKey = ($textureId << 4) | ($currentDirection & 0xF);
            $textureBitKeyFrame = $directionOffset[$textureBitKey] + $currentFrame[$id]; 

            $dx = (int)$xArr[$id];
            $dy = (int)$yArr[$id];
            $w = $rectW[$textureBitKeyFrame];
            $h = $rectH[$textureBitKeyFrame];

            if ($dx + $w < 0 || $dx > $screenWidth || 
                $dy + $h < 0 || $dy > $screenHeight) {
                continue;
            }

            $rectSource->x = $rectX[$textureBitKeyFrame];
            $rectSource->y = $rectY[$textureBitKeyFrame];
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