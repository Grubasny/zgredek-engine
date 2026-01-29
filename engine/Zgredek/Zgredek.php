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
        $dependencies = $this->dependencies;

        $libraries = $dependencies->libraries;
        $libSdl = $libraries->sdl;
        $libSdlImage = $libraries->sdlImage;

        $states = $dependencies->states;
        $characterState = $states->characterState;
        $textureState = $states->textureState;
        
        $windowManager = $dependencies->windowManager;
        
        $gameController = $windowManager->gameController;
        $playerSystem = $dependencies->playerSystem;
        $playetManager = $playerSystem->playerManager;

        $renderer = $windowManager->renderer;
        [$window, $sdlRenderer] = $windowManager->init($title, $width, $height, $fullscreen);

        $dependencies->characterTextureLoader->bake($sdlRenderer);
        $playetManager->init();
        
        $running = true;
        $lastTime = $libSdl->SDL_GetTicks();

        while ($running) {
            $currentTime = $libSdl->SDL_GetTicks();
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

            $renderer->clear($sdlRenderer);
            $renderer->drawCharacters($sdlRenderer, $states);
            $renderer->present($sdlRenderer);

            usleep(5000); 
        }
    }

    public function characterPath(string $path): string
    {
        return __DIR__ . "/../../data/characters/" . $path;
    }
}