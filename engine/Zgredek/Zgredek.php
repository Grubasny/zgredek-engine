<?php

namespace ZgredekEngine\Zgredek;

use ZgredekEngine\Input\Interfaces\Action;
use ZgredekEngine\Managers\Characters\PlayerManager;

class Zgredek
{
    public function __construct(
        private ?ZgredekDependencies $dependencies = null
    ) {
        if ($dependencies === null) {
            $this->dependencies = ZgredekDependencies::createDefault();
        }
    }

    public function setupPlayer(): PlayerManager
    {
        return $this->dependencies->playerSystem->playerManager;
    }

    public function run(
        string $title = 'New Game', 
        int $width = WindowManager::DEFAULT_WINDOW_WIDTH, 
        int $height = WindowManager::DEFAULT_WINDOW_HEIGHT, 
        bool $fullscreen = true
    ): void {
        $sdl = $this->dependencies->windowManager->sdl;
        $renderer = $this->dependencies->renderer;
        $gameController = $this->dependencies->gameController;
        $playerSystem = $this->dependencies->playerSystem;

        $characterState = $this->dependencies->characterState;
        $textureState = $this->dependencies->textureState;

        $this->dependencies->windowManager->init($title, $width, $height, $fullscreen);

        $running = true;
        $lastTime = $sdl->SDL_GetTicks();

        $this->dependencies->characterTextureLoader->bake();

        while ($running) {
            $currentTime = $sdl->SDL_GetTicks();
            $deltaTime = ($currentTime - $lastTime) / 1000.0;
            $lastTime = $currentTime;

            if ($deltaTime > 0.1) {
                $deltaTime = 0.1;
            }

            $actions = $gameController->processEvents();
            if ($actions & Action::QUIT) {
                $running = false;
            }

            $playerSystem->update($actions, $deltaTime);

            $renderer->clear();
            $renderer->drawCharacters($characterState, $textureState);
            $renderer->present();

            usleep(1000); 
        }
    }

    public function characterPath(string $path): string
    {
        return __DIR__ . "/../../data/characters/" . $path;
    }
}