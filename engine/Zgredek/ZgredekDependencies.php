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
        public readonly Libraries $libraries,
        public readonly WindowManager $windowManager,
        public readonly PlayerSystem $playerSystem,
        public readonly States $states,
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
        );
        $windowManager = new WindowManager($libraries, new Renderer($libraries), new GameController($libraries->sdl));

        $textureLoader = new CharacterTextureLoader($libraries, $states->textureState);        
        $playerManager = new PlayerManager($textureLoader, $states);

        return new self(
            $libraries,
            $windowManager,
            new PlayerSystem($playerManager, $states),
            $states,
            $textureLoader
        );
    }
}