<?php

namespace ZgredekEngine\Input\Interfaces;

interface Action {
    public const MOVE_UP    = 1;
    public const MOVE_DOWN  = 2;
    public const MOVE_LEFT  = 4;
    public const MOVE_RIGHT = 8;
    public const ATTACK     = 16;
    public const QUIT       = 32;
    public const DEBUG      = 64;
}