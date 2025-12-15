@extends(config('watermark.admin_layout', 'layouts.app'))

@section('content')
<div style="max-width:900px;margin:auto;padding:20px">

    <h2 style="margin-bottom:20px">Image Watermark Settings</h2>

    @if(session('success'))
        <div style="padding:10px;background:#e6fffa;color:#065f46;margin-bottom:15px">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Enable --}}
        <div style="margin-bottom:15px">
            <label>
                <input type="checkbox" name="use_image_watermark"
                    {{ optional($settings)->use_image_watermark ? 'checked' : '' }}>
                Enable Watermark
            </label>
        </div>

        {{-- Type --}}
        <div style="margin-bottom:15px">
            <label>Watermark Type</label><br>
            <select name="image_watermark_type" id="wm_type">
                <option value="text" {{ optional($settings)->image_watermark_type === 'text' ? 'selected' : '' }}>Text</option>
                <option value="image" {{ optional($settings)->image_watermark_type === 'image' ? 'selected' : '' }}>Image</option>
            </select>
        </div>

        {{-- TEXT OPTIONS --}}
        <div id="text_fields">
            <div style="margin-bottom:10px">
                <label>Watermark Text</label>
                <input type="text" name="watermark_text"
                    value="{{ $settings->watermark_text ?? '' }}">
            </div>

            <div style="display:flex;gap:10px;margin-bottom:10px">
                <div>
                    <label>Text Size</label>
                    <input type="number" name="watermark_text_size"
                        value="{{ $settings->watermark_text_size ?? 20 }}">
                </div>

                <div>
                    <label>Text Color</label>
                    <input type="color" name="watermark_text_color"
                        value="{{ $settings->watermark_text_color ?? '#cccccc' }}">
                </div>
            </div>
        </div>

        {{-- IMAGE OPTIONS --}}
        <div id="image_fields" style="display:none">
            <label>Watermark Image</label><br>
            <input type="file" name="watermark_image">

            @if(!empty($settings->watermark_image))
                <div style="margin-top:10px">
                    <img src="{{ asset('storage/'.$settings->watermark_image) }}"
                         style="max-height:80px;border:1px solid #ddd">
                </div>
            @endif
        </div>

        {{-- Position --}}
        <div style="margin-top:15px">
            <label>Position</label><br>
            <select name="watermark_position">
                @foreach(['top-left','top-right','center','bottom-left','bottom-right'] as $pos)
                    <option value="{{ $pos }}"
                        {{ optional($settings)->watermark_position === $pos ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $pos)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top:25px">
            <button type="submit" style="padding:10px 20px">
                Save Settings
            </button>
        </div>
    </form>
</div>

{{-- Simple dependency JS --}}
<script>
function toggleFields() {
    const type = document.getElementById('wm_type').value;
    document.getElementById('text_fields').style.display = type === 'text' ? 'block' : 'none';
    document.getElementById('image_fields').style.display = type === 'image' ? 'block' : 'none';
}
document.getElementById('wm_type').addEventListener('change', toggleFields);
toggleFields();
</script>
@endsection
