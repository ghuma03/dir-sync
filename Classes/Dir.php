<?php

declare(strict_types=1);

namespace Classes;

use \Exception;

class Dir {

    private string $path;
    private array $directories = [];
    private array $files = [];

    public function __construct(string $path) {

        if ( ! is_dir($path) ) {
            throw new Exception("Path informado não é um diretório: $path");
        }

        $this->path = $path;
    }

    public function get(string $prop) {
        return $this->$prop;
    }

    private function addDir(Dir $dir) {
        $this->directories[] = $dir;
    }

    private function addFile(File $file) {
        $this->files[] = $file;
    }

    public function openDirAndSetContent() {

        $handle = @opendir($this->path);

        if ($handle === false) {
            throw new Exception("Não foi possivel abrir " . $this->path);
        }
        
        while ( ($entry = readdir($handle)) !== false ) {

            if ( ! in_array($entry, [".", ".."]) ) {

                if ( is_dir($this->path . "/" . $entry) ) {

                    $dir = new Dir($this->path . "/" . $entry);
                        $dir->openDirAndSetContent();

                    $this->addDir($dir);
                }
                elseif ( is_file($this->path . "/" . $entry) ) {
                    $this->addFile( new File($this->path . "/" . $entry) );
                }
            }
        }

        closedir($handle);
    }
}