<?php

namespace ZgredekEngine\State;

class States
{
    public function __construct(
        public CharacterState $characterState,
        public TextureState $textureState,
    ) {}
}