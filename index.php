<?php

use ZgredekEngine\Managers\Characters\Interfaces\Direction;
use ZgredekEngine\Zgredek\Zgredek;

require_once __DIR__ . '/engine/bootstrap.php';

$zgredek = new Zgredek();
$playerManager = $zgredek->setupPlayer();
$playerManager->registerTexture($zgredek->characterPath('wloczykij-removebg-preview.png'), 'player');

$topPadding = 58;
$height = 127;
$width = 123;

$playerManager->setupTextureHorizontalGrid(
    'player', 
    Direction::IDLE,
    0, $topPadding,
    $width, $height,
    4
);
$playerManager->setupTextureHorizontalGrid(
    'player', 
    Direction::LEFT,
    0, $topPadding + $height,
    $width, $height,
    4
);
$playerManager->setupTextureHorizontalGrid(
    'player', 
    Direction::RIGHT,
    0, $topPadding + $height * 2,
    $width, $height,
    4
);

$zgredek->run();