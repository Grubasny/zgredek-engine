<?php

namespace ZgredekEngine\Systems;

use ZgredekEngine\State\TextureState;
use ZgredekEngine\State\CharacterState;

function checkCollisions(
    int $id, 
    CharacterState $characterState,
    TextureState $textureState,
): bool {
    $x = $characterState->x;
    $y = $characterState->y;
    $direction = $characterState->direction;
    $currentFrame = $characterState->currentFrame;
    $active = $characterState->active;
    $directionOffset = $textureState->directionOffset; 
    $rectW = $textureState->rectW;
    $rectH = $textureState->rectH;

    $bitKeyA = ($id << 4) | ($direction[$id] & 0xF);
    $keyA = $directionOffset[$bitKeyA] + $currentFrame[$id];
    
    $ax1 = $x[$id];
    $ay1 = $y[$id];
    $ax2 = $ax1 + $rectW[$keyA];
    $ay2 = $ay1 + $rectH[$keyA];

    foreach ($active as $targetId => $isActive) {
        if (!$isActive || $targetId === $id) {
            continue;
        }

        $bitKeyB = ($targetId << 4) | ($direction[$targetId] & 0xF);
        $keyB = $directionOffset[$bitKeyB] + $currentFrame[$targetId];

        if ($ax1 < $x[$targetId] + $rectW[$keyB] &&
            $ax2 > $x[$targetId] &&
            $ay1 < $y[$targetId] + $rectH[$keyB] &&
            $ay2 > $y[$targetId]) {
            return true;
        }
    }

    return false;
}