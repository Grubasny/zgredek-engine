<?php

namespace ZgredekEngine\Managers\Characters;

use Exception;
use ZgredekEngine\Loaders\CharacterTextureLoader;
use ZgredekEngine\Managers\Characters\Interfaces\Direction;
use ZgredekEngine\State\CharacterState;
use ZgredekEngine\State\States;
use ZgredekEngine\State\TextureState;

class PlayerManager
{
    public const TEXTURE_NAME = '__ZGREDEK__player';

    public ?int $id = null;
    protected string $name = '';

    public int $active;
    public float $x;
    public float $y;
    public int $direction;
    public int $currentFrame;
    public float $currentFrameTime;
    public int $hp;
    public int $maxHp;
    public array $frameCount = [
        Direction::IDLE => 0,
        Direction::UP => 0,
        Direction::RIGHT => 0,
        Direction::DOWN => 0,
        Direction::LEFT => 0,
    ];

    public CharacterState $characterState;
    public TextureState $textureState;

    public function __construct(
        private CharacterTextureLoader $textureLoader,
        public States $state, 
    ) {
        $characterState = $state->characterState;
        $this->characterState = $characterState;

        $textureState = $state->textureState;
        $this->textureState = $textureState;

        $id = $characterState->registerEntity();
        $this->id = $id;

        $this->active = &$characterState->active[$id];
        $this->x = &$characterState->x[$id];
        $this->y = &$characterState->y[$id];
        $this->direction = &$characterState->direction[$id];
        $this->currentFrame = &$characterState->currentFrame[$id];
        $this->currentFrameTime = &$characterState->currentFrameTime[$id]; 
        $this->hp = &$characterState->hp[$id];
        $this->maxHp = &$characterState->maxHp[$id];
    }

    public function init() {
        $textureState = $this->textureState;
        $id = $this->id;

        $characterBitKey = [
            Direction::IDLE => ($id << 4) | (Direction::IDLE & 0xF),
            Direction::UP => ($id << 4) | (Direction::UP & 0xF),
            Direction::RIGHT => ($id << 4) | (Direction::RIGHT & 0xF),
            Direction::DOWN => ($id << 4) | (Direction::DOWN & 0xF),
            Direction::LEFT => ($id << 4) | (Direction::LEFT & 0xF),            
        ];

        $textureBitKey = [
            Direction::IDLE => ($textureState->characterTextures[$characterBitKey[Direction::IDLE]] << 4) | (Direction::IDLE & 0xF),
            Direction::UP => ($textureState->characterTextures[$characterBitKey[Direction::UP]] << 4) | (Direction::UP & 0xF),
            Direction::RIGHT => ($textureState->characterTextures[$characterBitKey[Direction::RIGHT]] << 4) | (Direction::RIGHT & 0xF),
            Direction::DOWN => ($textureState->characterTextures[$characterBitKey[Direction::DOWN]] << 4) | (Direction::DOWN & 0xF),
            Direction::LEFT => ($textureState->characterTextures[$characterBitKey[Direction::LEFT]] << 4) | (Direction::LEFT & 0xF),    
        ];

        $this->frameCount = [
            Direction::IDLE => $textureState->frameCount[$textureBitKey[Direction::IDLE]] ?? 1,
            Direction::UP => $textureState->frameCount[$textureBitKey[Direction::UP]] ?? 1,
            Direction::RIGHT => $textureState->frameCount[$textureBitKey[Direction::RIGHT]] ?? 1,
            Direction::DOWN => $textureState->frameCount[$textureBitKey[Direction::DOWN]] ?? 1,
            Direction::LEFT => $textureState->frameCount[$textureBitKey[Direction::LEFT]] ?? 1,
        ];
    }
    
    public function registerTexture(string $path, string $textureName = self::TEXTURE_NAME)
    {
        $this->textureLoader->registerTexture($textureName, $path);
    }

    public function setPosition(float $x, float $y, float $deltaTime): self {
        $this->x = $x;
        $this->y = $y;
        
        $this->updateFrame($deltaTime);

        return $this;
    }

    public function setHP(int $hp, int $maxHp): self {
        $this->hp = $hp;
        $this->maxHp = $maxHp;

        return $this;
    }

    public function getX(): int { 
        return (int)$this->x;
    }

    public function getY(): int { 
        return (int)$this->y; 
    }

    public function getCurrentDirection(): int 
    { 
        return $this->direction; 
    }

    public function getFrame(): int 
    { 
        return $this->currentFrame; 
    }

    public function updateFrame(float $deltaTime)
    {
        $currentFrameTime = &$this->currentFrameTime;
        $currentFrameTime += $deltaTime;

        if ($currentFrameTime > 0.08) {
            $this->nextFrame($this->frameCount[$this->direction]);
            $currentFrameTime = 0;
        } 
    }

    public function nextFrame(int $maxFrames): void 
    {
        $this->currentFrame = ($this->currentFrame + 1) % $maxFrames;
    }

    public function setDirection(int $direction): void {
        if ($direction === $this->direction) {
            return;
        }

        $this->direction = $direction;
        $this->currentFrame = 0;
    }
    
    public function setupTextureHorizontalGrid(
        string $textureName,
        int $direction,
        int $startX,
        int $startY,
        int $width,
        int $height,
        int $count,
    ): void {
        $this->textureLoader->setupTextureHorizontalGrid(
            $textureName,
            $this->id, 
            $direction,
            $startX,
            $startY,
            $width,
            $height,
            $count
        );
    }
}