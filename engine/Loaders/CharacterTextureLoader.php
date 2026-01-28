<?php

namespace ZgredekEngine\Loaders;

use FFI;
use ZgredekEngine\State\TextureState;
use ZgredekEngine\Exceptions\SDLImgLoadException;
use ZgredekEngine\Exceptions\SDLWindowNotInitializedException;
use ZgredekEngine\Exceptions\TextureNotRegisteredException;
use ZgredekEngine\Zgredek\WindowManager;

class CharacterTextureLoader
{
    private array $texturePaths = [];
    private array $textureMap   = [];
    private array $rawFrames    = [];
    private int $nextTextureId = 1;

    public function __construct(
        private $sdl, 
        private $sdlImage,
        private WindowManager $windowManager,
        private TextureState $textureState
    ) {}

    public function registerTexture(string $textureName, string $path): int
    {
        if (isset($this->textureMap[$textureName])) {
            return $this->textureMap[$textureName];
        }
        
        $textureId = $this->nextTextureId++;

        $this->textureMap[$textureName] = $textureId;
        $this->texturePaths[$textureId] = $path;

        return $textureId;
    }

    public function setupTextureHorizontalGrid(
        string $textureName,
        int $characterId,
        int $direction,
        int $startX,
        int $startY,
        int $width,
        int $height,
        int $count,
    ): void {
        $textureId = $this->textureMap[$textureName] 
            ?? throw new TextureNotRegisteredException($textureName);

        for ($i = 0; $i < $count; $i++) {
            $bitKey = $characterId << 4 | ($direction & 0xF);

            if (!isset($this->rawFrames[$textureId][$bitKey])) {
                $this->rawFrames[$textureId][$bitKey] = [
                    'count' => $count,
                    'frames' => []
                ]; 
            }

            $this->rawFrames[$textureId][$bitKey]['frames'][] = [
                $startX + ($i * $width), 
                $startY,
                $width,
                $height,
            ];
        }
    }

    public function bake(): TextureState
    {
        $sdl = $this->sdl;
        $sdlImage = $this->sdlImage;
        $sdlRenderer = $this->windowManager->sdlRenderer ?? throw new SDLWindowNotInitializedException($this->sdl);

        $textureMap = $this->textureMap;
        $texturePaths = $this->texturePaths;
        $rawFrames = $this->rawFrames;

        $characterTextures = [];
        $textures = [];
        $directionOffset = [];
        $frameCount = [];
        $rectX = []; 
        $rectY = []; 
        $rectW = []; 
        $rectH = [];
        $globalIdx = 0;

        foreach ($textureMap as $textureId) {
            $path = $texturePaths[$textureId];
            $surface = $sdlImage->IMG_Load($path);

            if ($surface === null) {
                throw new SDLImgLoadException($path, $this->sdl);
            }
            
            if (FFI::isNull($surface)) {
                throw new SDLImgLoadException($path, $this->sdl);
            }

            $textures[$textureId] = $sdl->SDL_CreateTextureFromSurface($sdlRenderer, $surface);
            $sdl->SDL_FreeSurface($surface);

            if (!isset($rawFrames[$textureId])) {
                continue;
            }

            foreach($rawFrames[$textureId] as $bitKey => $rawFrame) {
                $fCount = $rawFrame['count'];
                $frames = $rawFrame['frames'];

                $directionOffset[$bitKey] = $globalIdx;

                /**
                 * @todo do not duplicate textures data
                 */
                $characterTextures[$bitKey] = $textureId;
                $frameCount[$bitKey] = $fCount;
                
                foreach ($frames as $frame) {
                    $rectX[$globalIdx] = $frame[0];
                    $rectY[$globalIdx] = $frame[1];
                    $rectW[$globalIdx] = $frame[2];
                    $rectH[$globalIdx] = $frame[3];

                    $globalIdx++;
                }
            }
        }

        $this->rawFrames = [];

        $textureState = $this->textureState;
        $textureState->textures = $textures;
        $textureState->characterTextures = $characterTextures;
        $textureState->directionOffset = $directionOffset;
        $textureState->frameCount = $frameCount;
        $textureState->rectX = $rectX;
        $textureState->rectY = $rectY;
        $textureState->rectW = $rectW;
        $textureState->rectH = $rectH;

        return $textureState;
    }
}