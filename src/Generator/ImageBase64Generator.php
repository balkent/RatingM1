<?php

namespace App\Generator;

class ImageBase64Generator
{
    public function __construct(
        private string $imageDirectory
    ) {
    }

    public function generate(string $picturePath): string
    {
        $path = $this->imageDirectory.'/'.$picturePath;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
