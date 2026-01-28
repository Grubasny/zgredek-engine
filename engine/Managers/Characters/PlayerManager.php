<?php

namespace ZgredekEngine\Managers\Characters;

use Exception;
use ZgredekEngine\Loaders\CharacterTextureLoader;
use ZgredekEngine\Managers\Characters\Interfaces\Direction;
use ZgredekEngine\State\CharacterState;

class PlayerManager
{
    public const TEXTURE_NAME = '__ZGREDEK__player';

    public ?int $id = null;
    
    protected string $name = '';

    public ?int $textureId = null;

    public function __construct(
        private CharacterTextureLoader $textureLoader, 
        public CharacterState $characterState,
    ) {
        $this->id = $characterState->registerEntity();
    }
    
    public function registerTexture(string $path, string $textureName = self::TEXTURE_NAME)
    {
        $this->textureId = $this->textureLoader->registerTexture($textureName, $path);
    }

    public function setTextureId(int $textureId): self {
        $this->textureId = $textureId;

        return $this;
    }

    public function setPosition(float $x, float $y): self {
        $this->characterState->x[$this->id] = $x;
        $this->characterState->y[$this->id] = $y;

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
        return $this->currentDirection; 
    }

    public function getFrame(): int 
    { 
        return $this->currentFrame; 
    }

    public function getTextureId(): int 
    {
        return $this->textureId; 
    }

    public function nextFrame(int $maxFrames): void 
    {
        $this->characterState->currentFrame[$this->id] = 
            ($this->characterState->currentFrame[$this->id] + 1) % $maxFrames;
    }

    public function setDirection(int $direction): void {
        $this->characterState->direction[$this->id] = $direction;
        $this->characterState->currentFrame[$this->id] = 0;
    }
}