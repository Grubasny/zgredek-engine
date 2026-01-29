<?php

namespace ZgredekEngine\Loaders;

use FFI;
use ZgredekEngine\State\TextureState;
use ZgredekEngine\Exceptions\SDLImgLoadException;
use ZgredekEngine\Exceptions\SDLWindowNotInitializedException;
use ZgredekEngine\Exceptions\TextureNotRegisteredException;
use ZgredekEngine\Lib\Libraries;
use ZgredekEngine\Lib\SDL2ImageInterface;
use ZgredekEngine\Lib\SDL2Interface;
use ZgredekEngine\Zgredek\WindowManager;

class CharacterTextureLoader
{
    private array $texturePaths = [];
    private array $textureMap   = [];
    private array $rawFrames    = [];
    private int $nextTextureId = 1;

    /** @var SDL2Interface $sdl */
    private $sdl;

    /** @var SDL2ImageInterface $sdlImage */
    private $sdlImage;

    public function __construct(
        Libraries $libraries,
        private TextureState $textureState
    ) {
        $this->sdl = $libraries->sdl;
        $this->sdlImage = $libraries->sdlImage;
    }

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
            if (!isset($this->rawFrames[$textureId][$direction])) {
                $this->rawFrames[$textureId][$direction] = [
                    'count' => $count,
                    'frames' => [],
                    'characters' => [],
                ]; 
            }

            $this->rawFrames[$textureId][$direction]['frames'][] = [
                $startX + ($i * $width), 
                $startY,
                $width,
                $height,
            ];

            $this->rawFrames[$textureId][$direction]['characters'][] = $characterId;
        }
    }

    public function bake($sdlRenderer): TextureState
    {
        $sdl = $this->sdl;
        $sdlImage = $this->sdlImage;
        $sdlRenderer = $sdlRenderer;

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

            foreach($rawFrames[$textureId] as $direction => $rawFrame) {
                $fCount = $rawFrame['count'];
                $frames = $rawFrame['frames'];
                $characters = $rawFrame['characters'];

                $bitKey = ($textureId << 4) | ($direction & 0xF);
                $directionOffset[$bitKey] = $globalIdx;

                $frameCount[$bitKey] = $fCount;
                
                foreach ($frames as $frame) {
                    $rectX[$globalIdx] = $frame[0];
                    $rectY[$globalIdx] = $frame[1];
                    $rectW[$globalIdx] = $frame[2];
                    $rectH[$globalIdx] = $frame[3];

                    $globalIdx++;
                }

                foreach ($characters as $characterId) {
                    $characterBitKey = ($characterId << 4) | ($direction & 0xF);
                    $characterTextures[$characterBitKey] = $textureId;
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