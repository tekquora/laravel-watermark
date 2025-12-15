<?php
namespace Tekquora\Watermark\Listeners;

use Tekquora\Watermark\Events\ImageUploaded;
use Tekquora\Watermark\Services\WatermarkService;

class ApplyWatermarkListener
{
    public function __construct(protected WatermarkService $service) {}

    public function handle(ImageUploaded $event): void
    {
        $this->service->apply($event->absolutePath);
    }
}

