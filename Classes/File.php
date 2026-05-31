<?php

declare(strict_types=1);

namespace Classes;

class File {

    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

}