<?php

namespace Tekquora\Watermark\Models;

use Illuminate\Database\Eloquent\Model;

class WatermarkSetting extends Model
{
    protected $table = 'watermark_settings';

    protected $fillable = [
        'use_image_watermark',
        'image_watermark_type',
        'watermark_image',
        'watermark_text',
        'watermark_text_size',
        'watermark_text_color',
        'watermark_position'
    ];
}
