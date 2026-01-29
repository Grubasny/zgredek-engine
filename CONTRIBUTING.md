# Contributing to Zgredek Engine

The project is currently in its early stages (1 week old), but the ambitions are high. If you want to contribute to a high-performance PHP game engine that challenges the status quo, please follow these guidelines.

## How to Contribute
1. **Reporting Bugs:** If FFI explodes on your system, open an Issue. Provide your OS details, PHP version, and steps to reproduce.
2. **Pull Requests:** - Avoid "cosmetic" PRs (e.g., changing spaces to tabs or reformatting code style). Let's focus on functionality.
   - If you add a new feature, ensure it does not compromise performance (stick to DOD / 1D Arrays).
   - **Do not use external dependencies.** The goal is to keep the engine lightweight and independent. No `composer require` allowed unless it's a critical, engine-defining reason discussed beforehand.

## Architecture (The Holy Rules)
This engine is built for performance. When adding new entity states or systems:
* **NO** heavy object instantiation in the main game loop.
* **YES** use flat 1D arrays to ensure cache-friendliness and cpu-friendliness.
* **FFI:** Any new C definitions in `cdef` must be cross-platform compatible (Linux, Windows, and ARM64/Android).
* **Hybrid Design:** 
    * **OOP (Object-Oriented Programming):** Used for configuration, game logic definition, and creative workflow (Blueprints). It ensures the engine is intuitive and developer-friendly.
    * **DOD (Data-Oriented Design):** Used for the core engine execution. Entity states are stored in flat 1D arrays to maximize CPU cache hits and minimize PHP garbage collector overhead.
    * **Low-Level Integration:** Direct C bindings via PHP FFI for SDL2 rendering and event handling.
    * **Entity System:** High-level OOP Blueprints are "baked" into optimized DOD structures for high-performance runtime execution.

## Licensing & Ownership
By submitting a Pull Request, you agree to license your contribution under the **GNU General Public License v3 (GPL-3.0)**.

**Note:** This project follows a dual-licensing model. It is Open Source for educational and GPL-compliant use, but a separate commercial license is required for closed-source commercial products. By contributing, you acknowledge and accept this policy.

---
*Write code as if the person who has to maintain it is a violent psychopath who knows where you live.*