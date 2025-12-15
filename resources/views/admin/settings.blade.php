@extends(config('watermark.admin_layout'))

@section('content')
<div class="container">
    <h2>Image Watermark Settings</h2>

    <form method="POST">
        @csrf

        <label>
            <input type="checkbox" name="use_image_watermark"
                {{ optional($settings)->use_image_watermark ? 'checked' : '' }}>
            Enable Watermark
        </label>

        <hr>

        <label>Watermark Type</label>
        <select name="image_watermark_type">
            <option value="text">Text</option>
            <option value="image">Image</option>
        </select>

        <label>Text</label>
        <input type="text" name="watermark_text"
               value="{{ $settings->watermark_text ?? '' }}">

        <label>Text Size</label>
        <input type="number" name="watermark_text_size"
               value="{{ $settings->watermark_text_size ?? 20 }}">

        <label>Text Color</label>
        <input type="color" name="watermark_text_color"
               value="{{ $settings->watermark_text_color ?? '#cccccc' }}">

        <label>Position</label>
        <select name="watermark_position">
            <option value="top-left">Top Left</option>
            <option value="top-right">Top Right</option>
            <option value="center">Center</option>
            <option value="bottom-right">Bottom Right</option>
        </select>

        <br><br>
        <button type="submit">Save Settings</button>
    </form>
</div>
@endsection
