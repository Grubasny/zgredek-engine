<?php

namespace ZgredekEngine\State;

use ZgredekEngine\Managers\Characters\Interfaces\Direction;

class CharacterState {
    private int $nextId = 0;

    public array $active = [];
    public array $x = [];
    public array $y = [];
    public array $direction = [];
    public array $currentFrame = [];

    /**
     * @todo keep information about free IDs when delete records
     * @todo system to clear state when away (like 10 chunks away)
     */
    public function registerEntity(): int {
        $id = $this->nextId++;

        $this->active[$id] = true;
        $this->x[$id] = 0;
        $this->y[$id] = 0;
        $this->direction[$id] = Direction::IDLE;
        $this->currentFrame[$id] = 0;

        return $id;
    }
}