<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Filesystem\Filesystem;


class FileExistExtension extends AbstractExtension
{
    private $fileSystem;
    private $projectDir;

    public function __construct(Filesystem $fileSystem, string $projectDir)
    {
        $this->fileSystem = $fileSystem;
        $this->projectDir = $projectDir;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('file_exists', [$this, 'getUrlFile'])
        ];
    }

    public function getUrlFile(string $path): string
    {
        $path = "{$this->projectDir}/public/uploads/{$path}";
        if($this->fileSystem->exists($path)) {
            return $path;
        }
        return  "{$this->projectDir}/public/uploads/image-not-found.png";
    }

}