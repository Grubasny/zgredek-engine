# Zgredek Engine

A performance-oriented 2D game engine built with PHP 8.3+, FFI, and SDL2. 
Project is currently 1 week old and undergoing rapid development.

## Core Architecture
* **Memory Management:** Data-Oriented Design (DOD). Entity states are stored in flat 1D arrays to maximize CPU cache hits and minimize PHP garbage collector overhead.
* **Low-Level Integration:** Direct C bindings via PHP FFI for SDL2 rendering and event handling.
* **Entity System:** Object-Oriented creation layer (Blueprints) "baked" into optimized data structures for runtime execution.

## Development Roadmap
- [x] SDL2 FFI Basic Integration & Rendering
- [x] Data-Oriented State Management (1D Arrays)
- [x] Basic Player Handling & Input Mapping
- [x] Game Controller (Action-based Input Drivers)
- [x] Collision System (AABB / Grid-based)
- [ ] Mob Management (Massive entity spawning)
- [ ] NPC System (State-driven actors)
- [ ] Auto-Dicing Sprite Analysis (Coming soon)
- [x] Linux Build (Docker/Native)
- [ ] Windows Build (.exe standalone)
- [ ] Android Build (ARM64 APK)

## Requirements## Requirements
* PHP 8.3 or higher (with `ffi.enable=true`)
* SDL2, SDL2_image, SDL2_ttf
* **Linux/Native:** Recommended for best performance and stability.
* **Docker:** Supported, but GUI passthrough (X11/Wayland) can be tricky. Use only if you know your way around `DISPLAY` env and XServer permissions. Here few things that could help you
    * `sudo xhost +local:docker`
    * `docker container run --rm -v $(pwd):/app/     -v /tmp/.X11-unix:/tmp/.X11-unix     -e DISPLAY=$DISPLAY     -e XDG_RUNTIME_DIR=/tmp     zgredek-php php index.php`


## License
This project is licensed under the **GNU General Public License v3 (GPL-3.0)**. 
* **Open Source:** Free for educational, research, and GPL-compliant open-source projects.
* **Commercial Use:** If you intend to use this engine for closed-source commercial products, a separate commercial license is required. Contact the author for terms.
