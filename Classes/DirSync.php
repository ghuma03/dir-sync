<?php

declare(strict_types=1);

namespace Classes;

class DirSync {

    private Dir $origin, $target;

    public function __construct(Dir $origin, Dir $target) {
        $this->origin = $origin;
        $this->target = $target;
    }

    public function syncDirectories() {
        print_r($this->origin->getDirContentAsAssoc());
    }
}