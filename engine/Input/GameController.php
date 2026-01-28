<?php

namespace ZgredekEngine\Input;

use FFI;
use SDL2Interface;
use ZgredekEngine\Exceptions\SDLNewException;
use ZgredekEngine\Input\Interfaces\Action;
use ZgredekEngine\Input\Interfaces\Event;
use ZgredekEngine\Input\Interfaces\Key;

class GameController {
    private array $actionStates = [
        Action::MOVE_UP => false,
        Action::MOVE_DOWN => false,
        Action::MOVE_LEFT => false,
        Action::MOVE_RIGHT => false,
        Action::ATTACK => false,
        Action::QUIT => false,
        Action::DEBUG => false,
    ];

    private $event;
    private $eventAddr;

    /**
     * @param SDL2Interface $sdl
     */
    public function __construct(private $sdl) {
        $this->event = $sdl->new('SDL_Event') ?? throw new SDLNewException('SDL_Event', $sdl);
        $this->eventAddr = FFI::addr($this->event);
    }

    public function processEvents(): int 
    {
        $event = $this->event;
        $eventAddr = $this->eventAddr;

        while ($this->sdl->SDL_PollEvent($eventAddr) != 0) {
            if ($event->type == Event::QUIT) {
                $this->set(Action::QUIT, true);
            }
            
            if ($event->type == Event::KEY_DOWN || $event->type == Event::KEY_UP) {
                $this->handleKeyboard();
            }
        }

        return array_sum(array_keys(array_filter($this->actionStates, null)));
    }

    private function handleKeyboard(): void {
        $event = $this->event;
        $key = $this->event->key->keysym->sym;
        $isDown = ($event->type == Event::KEY_DOWN);

        match($key) {
            Key::W => $this->set(Action::MOVE_UP, $isDown),   
            Key::S => $this->set(Action::MOVE_DOWN, $isDown), 
            Key::A => $this->set(Action::MOVE_LEFT, $isDown), 
            Key::D => $this->set(Action::MOVE_RIGHT, $isDown),
            Key::ENTER => $this->set(Action::ATTACK, $isDown),
            Key::Q => $this->set(Action::QUIT, $isDown),      
            Key::TILDE => $this->set(Action::DEBUG, $isDown),
            default => null
        };
    }

    private function set(int $action, bool $state): void {
        $this->actionStates[$action] = $state;
    }

    public function isActionActive(int $action): bool {
        return $this->actionStates[$action] ?? false;
    }

    public function isKeyboardEvent(int $type): bool {
        return $type === Event::KEY_DOWN || $type === Event::KEY_UP;
    }

    public function isMouseEvent(int $type): bool {
        return $type >= Event::MOUSE_MOTION && $type <= Event::MOUSE_WHEEL;
    }
}