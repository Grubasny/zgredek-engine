<?php

namespace ZgredekEngine\Loaders\FFI;

abstract class AbstractLoader {
    private array $types = [];
    private array $functions = [];

    abstract protected function getLibraryName(): string;
    abstract protected function getRequiredTypes(): array;
    abstract protected function getRequiredFunctions(): array;

    public function addType(string $definition): void {
        $this->types[] = $definition;
    }

    public function addFunction(string $definition): void {
        $this->functions[] = $definition;
    }

    public function build(): string {
        $typesArr = array_merge($this->getRequiredTypes(), $this->types);
        $functionsArr = array_merge($this->getRequiredFunctions(), $this->functions);

        $types = !empty($typesArr) 
            ? 'typedef ' . implode(";\ntypedef ", $typesArr) . ';' 
            : '';

        $functions = !empty($functionsArr) 
            ? implode(";\n", $functionsArr) . ';'
            : '';

        return $types . "\n\n" . $functions;
    }

    public function create(): \FFI {
        return \FFI::cdef($this->build(), $this->getLibraryName());
    }
}