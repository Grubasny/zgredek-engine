<?php

use ZgredekEngine\Managers\Characters\Interfaces\Direction;
use ZgredekEngine\Zgredek\Zgredek;

require_once __DIR__ . '/engine/bootstrap.php';

$zgredek = new Zgredek();

$texturePath = $zgredek->characterPath('zgredek.png');
$textureName = 'zgredek';
$playerManager = $zgredek->setupPlayer();
$playerManager->registerTexture($texturePath, $textureName);

$topPadding = 58;
$height = 127;
$width = 123;

$box = 300;

$zgredekSpriteSheetdirections = [
    Direction::UP,
    Direction::RIGHT,
    Direction::LEFT,
    Direction::DOWN,
];

foreach ($zgredekSpriteSheetdirections as $key => $direction) {
    $playerManager->setupTextureHorizontalGrid(
        $textureName, 
        $direction,
        0, $box * $key,
        $box, $box,
        6
    );
}

$playerManager->setupTextureHorizontalGrid(
    $textureName, 
    Direction::IDLE,
    0, $box * $key,
    $box, $box,
    6
);

$zgredek->run();