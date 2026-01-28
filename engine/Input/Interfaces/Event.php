<?php

namespace ZgredekEngine\Input\Interfaces;

class Event {
    // System
    public const QUIT = 0x100; 
    public const WINDOW = 0x200; 

    // Keyboard
    public const KEY_DOWN = 0x300; 
    public const KEY_UP = 0x301; 

    // Mouse
    public const MOUSE_MOTION = 0x400; 
    public const MOUSE_DOWN = 0x401; 
    public const MOUSE_UP = 0x402; 
    public const MOUSE_WHEEL = 0x403; 

    // Gampad / Joystick
    public const JOY_AXIS = 0x600;
    public const JOY_BUTTON_DOWN = 0x603;
    public const CONTROLLER_BUTTON_DOWN = 0x650;

}