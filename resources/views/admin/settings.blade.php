@extends(config('watermark.admin_layout', 'layouts.app'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Image Watermark Settings</h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Enable --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox"
                                   name="use_image_watermark" id="use_image_watermark"
                                   {{ optional($settings)->use_image_watermark ? 'checked' : '' }}>
                            <label class="form-check-label">Enable Watermark</label>
                            <input type="hidden" name="image_watermark_type" id="wm_type" value="image">
                        </div>

                        {{-- Type --}}
                        <!-- <div class="mb-3">
                            <label class="form-label">Watermark Type</label>
                            <select name="image_watermark_type" id="wm_type" class="form-control">
                                <option value="image" {{ optional($settings)->image_watermark_type === 'image' ? 'selected' : '' }}>Image</option>
                                <option value="text" {{ optional($settings)->image_watermark_type === 'text' ? 'selected' : '' }}>Text</option>
                            </select>
                        </div> -->

                        {{-- TEXT OPTIONS --}}
                        <div id="text_fields">
                            <div class="mb-3">
                                <label class="form-label">Watermark Text</label>
                                <input type="text" name="watermark_text" class="form-control"
                                       value="{{ $settings->watermark_text ?? '' }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Text Size</label>
                                    <input type="number" name="watermark_text_size" class="form-control"
                                           value="{{ $settings->watermark_text_size ?? 20 }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Text Opacity (<small class="text-muted">
                                        <span>{{ $settings->watermark_text_opacity ?? 40 }}%</span>
                                        </small>):
                                    </label>

                                    <input
                                        type="range"
                                        name="watermark_text_opacity"
                                        class="form-range"
                                        min="5"
                                        max="100"
                                        value="{{ $settings->watermark_text_opacity ?? 40 }}"
                                        oninput="this.parentElement.querySelector('span').innerText = this.value + '%'"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Text Color</label>
                                    <input type="color" name="watermark_text_color"
                                           class="form-control form-control-color"
                                           value="{{ $settings->watermark_text_color ?? '#cccccc' }}">
                                </div>
                            </div>
                        </div>

                        {{-- IMAGE OPTIONS --}}
                        <div id="image_fields" style="display:none">
                            <div class="mb-3">
                                <label class="form-label">Watermark Image</label>
                                <input type="file" name="watermark_image"
                                       class="form-control" accept="image/*"
                                       onchange="handleWatermarkUpload(this)">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Size (<small class="text-muted">
                                        <span>{{ $settings->watermark_image_size ?? 30 }}%</span>
                                    </small>)</label>
                                    <input type="range" name="watermark_image_size"
                                           class="form-range" min="5" max="100"
                                           value="{{ $settings->watermark_image_size ?? 30 }}"
                                           oninput="this.parentElement.querySelector('span').innerText = this.value + '%'">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Opacity (<small class="text-muted">
                                        <span>{{ $settings->watermark_image_opacity ?? 30 }}%</span>
                                    </small>)</label>
                                    <input type="range" name="watermark_image_opacity"
                                           class="form-range" min="5" max="100"
                                           value="{{ $settings->watermark_image_opacity ?? 30 }}"
                                           oninput="this.parentElement.querySelector('span').innerText = this.value + '%'">
                                </div>
                            </div>
                        </div>

                        {{-- PREVIEW (COMMON) --}}
                        <div class="mt-4">
                            <label class="form-label">Live Preview</label>
                            <div class="border p-2 bg-light">
                                <canvas id="wm_preview_canvas"
                                        style="max-width:100%;border:1px solid #ddd"></canvas>
                            </div>
                            <small class="text-muted">
                                Real-time preview
                            </small>
                        </div>

                        {{-- Position --}}
                        <div class="mt-4">
                            <label class="form-label">Watermark Position</label>
                            <select name="watermark_position" class="form-control">
                                @foreach(['top-left','top-right','center','bottom-left','bottom-right'] as $pos)
                                    <option value="{{ $pos }}"
                                        {{ optional($settings)->watermark_position === $pos ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $pos)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
function toggleFields() {
    const type = document.getElementById('wm_type').value;
    document.getElementById('text_fields').style.display = type === 'text' ? 'block' : 'none';
    document.getElementById('image_fields').style.display = type === 'image' ? 'block' : 'none';

    if (typeof window.drawPreview === 'function') {
        window.drawPreview();
    }
}

document.getElementById('wm_type').addEventListener('change', toggleFields);

document.addEventListener('DOMContentLoaded', () => {

    const canvas = document.getElementById('wm_preview_canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let previewImg = null;

    canvas.width = 600;
    canvas.height = 300;

    const qs = name => document.querySelector(`[name="${name}"]`);

    window.drawPreview = function () {
        const type = document.getElementById('wm_type').value;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background
        ctx.fillStyle = '#f3f4f6';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#e5e7eb';
        for (let x = 0; x < canvas.width; x += 40) {
            for (let y = 0; y < canvas.height; y += 40) {
                if ((x + y) % 80 === 0) ctx.fillRect(x, y, 40, 40);
            }
        }

        // IMAGE WATERMARK
        if (type === 'image' && previewImg) {
            const size = parseInt(qs('watermark_image_size')?.value || 30);
            const opacity = parseInt(qs('watermark_image_opacity')?.value || 30);

            const wmWidth = canvas.width * (size / 100);
            const scale = wmWidth / previewImg.width;
            const wmHeight = previewImg.height * scale;

            ctx.globalAlpha = opacity / 100;
            ctx.drawImage(
                previewImg,
                (canvas.width - wmWidth) / 2,
                (canvas.height - wmHeight) / 2,
                wmWidth,
                wmHeight
            );
            ctx.globalAlpha = 1;
        }

        // TEXT WATERMARK
        if (type === 'text') {
            const text = qs('watermark_text')?.value || 'Watermark Text';
            const size = parseInt(qs('watermark_text_size')?.value || 20);
            const color = qs('watermark_text_color')?.value || '#cccccc';
            const opacity = parseInt(qs('watermark_text_opacity')?.value || 40);
            
            ctx.globalAlpha = opacity / 100;
            // ctx.globalAlpha = 0.4;
            ctx.fillStyle = color;
            ctx.font = `${size}px Arial`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(text, canvas.width / 2, canvas.height / 2);
            ctx.globalAlpha = 1;
        }
    };

    window.handleWatermarkUpload = function (input) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.src = e.target.result;
            img.onload = () => {
                previewImg = img;
                window.drawPreview();
            };
        };
        reader.readAsDataURL(file);
    };

    document.addEventListener('input', e => {
        if (e.target.name?.startsWith('watermark_')) {
            window.drawPreview();
        }
    });

    @if(!empty($settings->watermark_image))
    const savedImg = new Image();
    savedImg.src = "{{ asset('storage/'.$settings->watermark_image) }}";
    savedImg.onload = () => {
        previewImg = savedImg;
        window.drawPreview();
    };
    @else
    window.drawPreview();
    @endif

    toggleFields();
});
</script>

@endsection
