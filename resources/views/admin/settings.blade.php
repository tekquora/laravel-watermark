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
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Enable Watermark --}}
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="use_image_watermark"
                                    id="use_image_watermark"
                                    {{ optional($settings)->use_image_watermark ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="use_image_watermark">
                                    Enable Watermark
                                </label>
                            </div>
                        </div>

                        {{-- Watermark Type --}}
                        <div class="form-group mb-3">
                            <label for="wm_type" class="form-label">
                                Watermark Type
                            </label>
                            <select
                                name="image_watermark_type"
                                id="wm_type"
                                class="form-control"
                            >
                                <option value="text" {{ optional($settings)->image_watermark_type === 'text' ? 'selected' : '' }}>
                                    Text
                                </option>
                                <option value="image" {{ optional($settings)->image_watermark_type === 'image' ? 'selected' : '' }}>
                                    Image
                                </option>
                            </select>
                        </div>

                        {{-- TEXT OPTIONS --}}
                        <div id="text_fields">
                            <div class="form-group mb-3">
                                <label class="form-label">Watermark Text</label>
                                <input
                                    type="text"
                                    name="watermark_text"
                                    class="form-control"
                                    value="{{ $settings->watermark_text ?? '' }}"
                                    placeholder="Enter watermark text"
                                >
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Text Size</label>
                                    <input
                                        type="number"
                                        name="watermark_text_size"
                                        class="form-control"
                                        value="{{ $settings->watermark_text_size ?? 20 }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Text Color</label>
                                    <input
                                        type="color"
                                        name="watermark_text_color"
                                        class="form-control form-control-color"
                                        value="{{ $settings->watermark_text_color ?? '#cccccc' }}"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- IMAGE OPTIONS --}}
                        <div id="image_fields" class="mb-3" style="display:none">
                            <label class="form-label">Watermark Image</label>
                            <input
                                type="file"
                                name="watermark_image"
                                class="form-control"
                            >

                            @if(!empty($settings->watermark_image))
                                <div class="mt-3">
                                    <img
                                        src="{{ asset('storage/'.$settings->watermark_image) }}"
                                        class="img-thumbnail"
                                        style="max-height:100px"
                                    >
                                </div>
                            @endif
                        </div>

                        {{-- Position --}}
                        <div class="form-group mb-4">
                            <label class="form-label">Watermark Position</label>
                            <select
                                name="watermark_position"
                                class="form-control"
                            >
                                @foreach(['top-left','top-right','center','bottom-left','bottom-right'] as $pos)
                                    <option value="{{ $pos }}"
                                        {{ optional($settings)->watermark_position === $pos ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $pos)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Save Settings
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Dependency Toggle Script --}}
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
