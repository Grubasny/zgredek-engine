<?php

namespace ZgredekEngine\Zgredek;

use ZgredekEngine\State\TextureState;
use ZgredekEngine\Graphic\Renderer;
use ZgredekEngine\Input\GameController;
use ZgredekEngine\Lib\Libraries;
use ZgredekEngine\Loaders\CharacterTextureLoader;
use ZgredekEngine\Loaders\FFI\SDLImageLoader;
use ZgredekEngine\Loaders\FFI\SDLLoader;
use ZgredekEngine\Managers\Characters\PlayerManager;
use ZgredekEngine\State\CharacterState;
use ZgredekEngine\State\States;
use ZgredekEngine\Systems\PlayerSystem;

class ZgredekDependencies {
    public function __construct(
        public readonly WindowManager $windowManager,
        public readonly GameController $gameController,
        public readonly PlayerSystem $playerSystem,
        public readonly Renderer $renderer,
        public readonly CharacterState $characterState,
        public readonly TextureState $textureState,
        public readonly CharacterTextureLoader $characterTextureLoader
    ) {}

    public static function createDefault(): self
    {
        $libraries = new Libraries(
            (new SDLLoader())->create(),
            (new SDLImageLoader())->create(),
        );

        $states = new States(
            new CharacterState(),
            new TextureState()
        )
        $windowManager = new WindowManager($libraries, new Renderer());

        $textureLoader = new CharacterTextureLoader($sdl, $sdlImage, $windowManager, $textureState);        
        $playerManager = new PlayerManager($textureLoader, $characterState);

        return new self(
            $windowManager,
            new GameController($sdl),
            new PlayerSystem($playerManager, $characterState, $textureState),
            $renderer,
            $characterState,
            $textureState,
            $textureLoader
        );
    }
}