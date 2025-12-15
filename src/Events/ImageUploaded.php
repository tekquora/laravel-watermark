<?php
namespace Tekquora\Watermark\Events;

class ImageUploaded
{
    public function __construct(
        public string $absolutePath
    ) {}
}

