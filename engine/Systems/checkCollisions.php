<?php

namespace ZgredekEngine\Systems;

use ZgredekEngine\State\TextureState;
use ZgredekEngine\State\CharacterState;

use function ZgredekEngine\Logger\log;

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
    $characterTextures = $textureState->characterTextures; 
    $rectW = $textureState->rectW;
    $rectH = $textureState->rectH;

    $characterBitKey = ($id << 4) | ($direction[$id] & 0xF);
    $characterTextureId = $characterTextures[$characterBitKey];
    $characterTextureBitKey = ($characterTextureId << 4) | ($direction[$id] & 0xF);
    $characterFrameOffset = $directionOffset[$characterTextureBitKey] + $currentFrame[$id];
    
    $ax1 = $x[$id];
    $ay1 = $y[$id];
    $ax2 = $ax1 + $rectW[$characterFrameOffset];
    $ay2 = $ay1 + $rectH[$characterFrameOffset];

    foreach ($active as $targetId => $isActive) {
        if (!$isActive || $targetId === $id) {
            continue;
        }

        /** @todo calculate bitKey in separate 1d arr */
        $targetBitKey = ($targetId << 4) | ($direction[$targetId] & 0xF);
        $targetTextureId = $characterTextures[$targetBitKey];
        $targetTextureBitKey = ($targetTextureId << 4) | ($direction[$targetId] & 0xF);
        $targetFrameOffset = $directionOffset[$targetTextureBitKey] + $currentFrame[$targetId];

        if ($ax1 < $x[$targetId] + $rectW[$targetFrameOffset] &&
            $ax2 > $x[$targetId] &&
            $ay1 < $y[$targetId] + $rectH[$targetFrameOffset] &&
            $ay2 > $y[$targetId]) {
            return true;
        }
    }

    return false;
}