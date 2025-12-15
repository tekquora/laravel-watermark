<?php
namespace Tekquora\Watermark\Services;

use Intervention\Image\ImageManagerStatic as Image;
use Tekquora\Watermark\Models\WatermarkSetting;

class WatermarkService
{
    public function apply(string $absoluteImagePath): void
    {
        $settings = WatermarkSetting::first();

        if (!$settings || !$settings->use_image_watermark) {
            return;
        }

        if (!file_exists($absoluteImagePath)) {
            return;
        }

        $img = Image::make($absoluteImagePath);

        if ($settings->image_watermark_type === 'image' && $settings->watermark_image) {
            $this->applyImageWatermark($img, $settings);
        }

        if ($settings->image_watermark_type === 'text') {
            $this->applyTextWatermark($img, $settings);
        }

        $img->save();
    }

    // protected function applyImageWatermark($img, $settings): void
    // {
    //     $watermarkPath = storage_path('app/public/' . $settings->watermark_image);
    //     if (!file_exists($watermarkPath)) return;

    //     $watermark = Image::make($watermarkPath);

    //     $img->insert(
    //         $watermark,
    //         $settings->watermark_position ?? 'bottom-right',
    //         10,
    //         10
    //     );
    // }

    protected function applyImageWatermark($img, $settings): void
    {
        $watermarkPath = storage_path('app/public/' . $settings->watermark_image);
        if (!file_exists($watermarkPath)) return;

        $watermark = Image::make($watermarkPath);

        // 1️⃣ Resize watermark (percentage of base image width)
        $baseWidth = $img->width();
        $targetWidth = intval($baseWidth * ($settings->watermark_image_size / 100));

        $watermark->resize($targetWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // 2️⃣ Apply opacity
        $opacity = max(0, min(100, $settings->watermark_image_opacity ?? 30));
        $watermark->opacity($opacity);

        // 3️⃣ Insert watermark
        $img->insert(
            $watermark,
            $settings->watermark_position ?? 'center',
            10,
            10
        );
    }


    protected function applyTextWatermark($img, $settings): void
    {
        [$x, $y, $align, $valign] = $this->resolvePosition(
            $img->width(),
            $img->height(),
            $settings->watermark_position
        );

        $img->text($settings->watermark_text ?? 'Watermark', $x, $y, function ($font) use ($settings, $align, $valign) {
            $font->size($settings->watermark_text_size ?? 20);
            $font->color($settings->watermark_text_color ?? '#cccccc');
            $font->align($align);
            $font->valign($valign);
        });
    }

    protected function resolvePosition($width, $height, $position): array
    {
        return match ($position) {
            'top-left'     => [20, 20, 'left', 'top'],
            'top-right'    => [$width - 20, 20, 'right', 'top'],
            'center'       => [$width / 2, $height / 2, 'center', 'middle'],
            'bottom-left'  => [20, $height - 20, 'left', 'bottom'],
            default        => [$width - 20, $height - 20, 'right', 'bottom'],
        };
    }
}
