<?php

use FFI;
use FFI\CData;

if (!class_exists('SDL2')) {
    class SDL2 extends FFI {
        public function SDL_Init(int $flags): int { return 0; }
        public function SDL_CreateWindow(string $title, int $x, int $y, int $w, int $h, int $flags): object { return (object)[]; }
        public function SDL_CreateRenderer(object $window, int $index, int $flags): object { return (object)[]; }
        public function SDL_PollEvent(object $event): int { return 0; }
        public function SDL_RenderClear(object $renderer): void {}
        public function SDL_RenderCopy(object $r, object $t, object $s, object $d): int { return 0; }
        public function SDL_RenderPresent(object $renderer): void {}
        public function SDL_DestroyRenderer(object $renderer): void {}
        public function SDL_DestroyWindow(object $window): void {}
        public function SDL_Quit(): void {}
        public function SDL_FreeSurface(object $surface): void {}
        public function SDL_CreateTextureFromSurface(object $renderer, object $surface): object { return (object)[]; }
        public function SDL_GetError(): string { return ""; }

        public static function new($type, bool $owned = true, bool $persistent = false): ?CData { return (object)[]; }
        public static function addr(CData $cdata): CData { return (object)[]; }
    }
}