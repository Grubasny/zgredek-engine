<?php

namespace ZgredekEngine\Systems;

use ZgredekEngine\State\TextureState;
use ZgredekEngine\Input\Interfaces\Action;
use ZgredekEngine\Managers\Characters\Interfaces\Direction;
use ZgredekEngine\Managers\Characters\PlayerManager;
use ZgredekEngine\State\CharacterState;
use ZgredekEngine\State\States;

/**
 * @todo - Player System should be in Manager 
 */
class PlayerSystem
{
    private float $speed = 200.0;

    private CharacterState $characterState; 
    private TextureState $textureState;

    public function __construct(
        public PlayerManager $playerManager,
        States $states,    
    ) {
        $this->characterState = $states->characterState;
        $this->textureState = $states->textureState;
    }

    public function update(int $actions, float $deltaTime): void
    {
        $dx = 0;
        $dy = 0;
        $direction = Direction::IDLE;

        if ($actions & Action::MOVE_UP) {
            $dy -= 1;
            $direction = Direction::UP;
        } elseif ($actions & Action::MOVE_DOWN) {
            $dy += 1;
            $direction = Direction::DOWN;
        }

        if ($actions & Action::MOVE_LEFT) {
            $dx -= 1;
            $direction = Direction::LEFT;
        } elseif ($actions & Action::MOVE_RIGHT) {
            $dx += 1;
            $direction = Direction::RIGHT;
        }

        $this->playerManager->setDirection($direction);
        
        if ($direction !== Direction::IDLE) {
            if (checkCollisions($this->playerManager->id, $this->characterState, $this->textureState)) {
                return;
            }

            $newX = $this->playerManager->x + ($dx * $this->speed * $deltaTime);
            $newY = $this->playerManager->y + ($dy * $this->speed * $deltaTime);

            $this->playerManager->setPosition($newX, $newY, $deltaTime);
        }
    }
}