<?php
namespace Tekquora\Watermark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tekquora\Watermark\Models\WatermarkSetting;

class WatermarkController extends Controller
{
    public function edit()
    {
        $settings = WatermarkSetting::first();
        return view('watermark::admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'image_watermark_type' => 'required|in:image,text',
            'watermark_text' => 'nullable|string',
            'watermark_text_size' => 'nullable|integer|min:8',
            'watermark_text_color' => 'nullable|string',
            'watermark_position' => 'required|string',
        ]);

        $data['use_image_watermark'] = $request->boolean('use_image_watermark');

        WatermarkSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Watermark settings updated successfully');
    }
}

